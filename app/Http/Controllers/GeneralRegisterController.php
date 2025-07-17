<?php

namespace App\Http\Controllers;

use App\Models\Applicants;
use App\Models\Concubines;
use App\Models\Kids;
use App\Models\AuthorizedPersons;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeneralRegisterController extends Controller
{
    //Registrar un solicitante con concubino, hijos y autorizaciones
    public function register(Request $request)
    {
        // Decodificar los datos JSON del request
        $applicantData = json_decode($request->applicantForm, true);
        $concubinesData = json_decode($request->concubinesForm, true);
        $kidsData = json_decode($request->kidForm, true);
        $authorizedPersonsData = json_decode($request->authorizationsForm, true);

        // Limpiar datos del solicitante
        $cleanApplicantData = [];
        foreach ($applicantData as $key => $value) {
            if (in_array($key, ['file'])) {
                continue; // Los archivos se manejan por separado
            }
            if (is_array($value) && empty($value)) {
                $cleanApplicantData[$key] = null;
            } else {
                $cleanApplicantData[$key] = $value;
            }
        }

        // Limpiar datos del concubino
        $cleanConcubinesData = [];
        foreach ($concubinesData as $key => $value) {
            if (in_array($key, ['file'])) {
                continue; // Los archivos se manejan por separado
            }
            if (is_array($value) && empty($value)) {
                $cleanConcubinesData[$key] = null;
            } else {
                $cleanConcubinesData[$key] = $value;
            }
        }

        // Registrar un solicitante
        $applicant = new Applicants($cleanApplicantData);
        $applicant->save();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'applicant_' . $applicant->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/public/files', $filename);
            $applicant->file = $path;
            $applicant->save();
        }

        // Registrar un concubino
        if (!Applicants::find($applicant->id)) {
            return response()->json(['error' => 'El solicitante proporcionado no existe.'], 404);
        }
        
        $concubine = new Concubines($cleanConcubinesData);
        $concubine->applicant_id = $applicant->id;
        $concubine->save();
        
        if ($request->hasFile('concubine_file')) {
            $file = $request->file('concubine_file');
            $filename = 'concubine_' . $concubine->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/files', $filename);
            $path = url('storage/public/files', $filename);
            $concubine->file = $path;
            $concubine->save();
        }

        // Registrar hijos array
        $kids = [];
        if (is_array($kidsData)) {
            foreach ($kidsData as $index => $kidData) {
                // Limpiar datos antes de crear el modelo
                $cleanKidData = [];
                foreach ($kidData as $key => $value) {
                    // Ignorar campos de archivo que vienen como objetos vacíos
                    if (in_array($key, ['file', 'insurance_file', 'vaccines_file'])) {
                        continue; // Los archivos se manejan por separado
                    }
                    
                    // Convertir objetos vacíos a null
                    if (is_array($value) && empty($value)) {
                        $cleanKidData[$key] = null;
                    } else {
                        $cleanKidData[$key] = $value;
                    }
                }
                
                $kid = new Kids($cleanKidData);
                $kid->applicant_id = $applicant->id;
                $kid->concubine_id = $concubine->id;
                
                if (isset($cleanKidData['born_date'])) {
                    $bornDate = $cleanKidData['born_date'];
                    
                    // Log para debugging
                    Log::info('Fecha recibida para niño: ' . json_encode($bornDate));
                    
                    // Intentar diferentes formatos de fecha
                    try {
                        // Si es un string ISO (formato de JavaScript Date)
                        if (is_string($bornDate) && strpos($bornDate, 'T') !== false) {
                            $kid->born_date = Carbon::parse($bornDate)->format('Y-m-d');
                            Log::info('Fecha parseada como ISO: ' . $kid->born_date);
                        }
                        // Si es formato dd/mm/yyyy
                        elseif (is_string($bornDate) && strpos($bornDate, '/') !== false) {
                            $kid->born_date = Carbon::createFromFormat('d/m/Y', $bornDate)->format('Y-m-d');
                            Log::info('Fecha parseada como dd/mm/yyyy: ' . $kid->born_date);
                        }
                        // Si es formato dd-mm-yyyy
                        elseif (is_string($bornDate) && strpos($bornDate, '-') !== false) {
                            $kid->born_date = Carbon::createFromFormat('d-m-Y', $bornDate)->format('Y-m-d');
                            Log::info('Fecha parseada como dd-mm-yyyy: ' . $kid->born_date);
                        }
                        // Si es un timestamp
                        elseif (is_numeric($bornDate)) {
                            $kid->born_date = Carbon::createFromTimestamp($bornDate)->format('Y-m-d');
                            Log::info('Fecha parseada como timestamp: ' . $kid->born_date);
                        }
                        // Si es un objeto Date de JavaScript (ya convertido a string)
                        else {
                            $kid->born_date = Carbon::parse($bornDate)->format('Y-m-d');
                            Log::info('Fecha parseada como string genérico: ' . $kid->born_date);
                        }
                    } catch (Exception $e) {
                        // Si no se puede parsear, usar la fecha actual
                        $kid->born_date = Carbon::now()->format('Y-m-d');
                        Log::warning('Error parseando fecha, usando fecha actual: ' . $e->getMessage());
                    }
                }

                $kid->save();

                $initials = strtoupper(substr($kid->name, 0, 1)) . strtoupper(substr($kid->last_name, 0, 1));
                $year = substr(\Carbon\Carbon::now()->year, -2);
                $id = str_pad($kid->id, 4, '0', STR_PAD_LEFT);

                $kid->code = $initials . $year . "-" . $id;

                // Manejar archivos del niño usando el índice
                if ($request->hasFile('kid_file_' . $index)) {
                    $file = $request->file('kid_file_' . $index);
                    $filename = 'kid_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/files', $filename);
                    $path = url('storage/public/files', $filename);
                    $kid->file = $path;
                }

                if ($request->hasFile('kid_insurance_file_' . $index)) {
                    $file = $request->file('kid_insurance_file_' . $index);
                    $filename = 'kid_insurance_file_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/files', $filename);
                    $path = url('storage/public/files', $filename);
                    $kid->insurance_file = $path;
                }

                if ($request->hasFile('kid_vaccines_file_' . $index)) {
                    $file = $request->file('kid_vaccines_file_' . $index);
                    $filename = 'kid_vaccines_file_' . $kid->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/files', $filename);
                    $path = url('storage/public/files', $filename);
                    $kid->vaccines_file = $path;
                }

                $kid->save();
                $kids[] = $kid;
            }
        }

        // Registrar autorizados array
        $authorizedPersons = [];
        if (is_array($authorizedPersonsData)) {
            foreach ($authorizedPersonsData as $index => $authorizedPersonData) {
                // Limpiar datos antes de crear el modelo
                $cleanAuthorizedPersonData = [];
                foreach ($authorizedPersonData as $key => $value) {
                    // Ignorar campos de archivo que vienen como objetos vacíos
                    if (in_array($key, ['file'])) {
                        continue; // Los archivos se manejan por separado
                    }
                    
                    // Convertir objetos vacíos a null
                    if (is_array($value) && empty($value)) {
                        $cleanAuthorizedPersonData[$key] = null;
                    } else {
                        $cleanAuthorizedPersonData[$key] = $value;
                    }
                }
                
                $authorizedPerson = new AuthorizedPersons($cleanAuthorizedPersonData);
                $authorizedPerson->applicant_id = $applicant->id;
                $authorizedPerson->save();
                
                // Manejar archivos de autorización usando el índice
                if ($request->hasFile('authorization_file_' . $index)) {
                    $file = $request->file('authorization_file_' . $index);
                    $filename = 'authorization_' . $authorizedPerson->id . '_' . now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/files', $filename);
                    $path = url('storage/public/files', $filename);
                    $authorizedPerson->file = $path;
                    $authorizedPerson->save();
                }
                
                $authorizedPersons[] = $authorizedPerson;
            }
        }

        $data = [
            'applicant' => $applicant,
            'concubine' => $concubine,
            'kids' => $kids,
            'authorizedPersons' => $authorizedPersons
        ];

        return response()->json(["data" => $data], 200);
    }


}
