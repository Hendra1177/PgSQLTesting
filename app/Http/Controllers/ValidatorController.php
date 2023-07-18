<?php

namespace App\Http\Controllers;

use App\Models\soal;
use App\Models\submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Test;
use Illuminate\Support\Facades\DB;

class ValidatorController extends Controller
{
    public $code, $isAllowSubmit = true;
    private  $jenis;
    protected function displayHint($myhint)
    {
        $hint = "<div class='alert alert-danger'>";
        $hint .= "<i class='fa-solid fa-triangle-exclamation'></i> " . $myhint;
        $hint .= "</div>";
        return $hint;
    }

    protected function displayError($myerror)
    {
        $error = "<div id='output-text' class='alert alert-danger'>";
        $error .= $myerror;
        $error .= "</div>";
        return $error;
    }

    public function connectToDatabase()
    {
        if (!pg_connect("host = localhost port = 5432 dbname=pgtest user=postgres  password=hendra11")) {
            throw new \Exception('SYSTEM_ERROR: Cant connect to database!');
        }
        return pg_connect("host = localhost port = 5432 dbname=pgtest user=postgres  password=hendra11");
        // $dbconn = pg_connect("host=localhost port=5432 dbname=Pgsqltest user=postgres password=hendra11");
        // if (!$dbconn) {
        //     die("koneksi gagal");
        // }

        // $result = pg_query($dbconn );

    }

    public function disconnectFromDatabase($connection)
    {
        if (!pg_close($connection)) {
            throw new \Exception('SYSTEM_ERROR: Cant disconnect from database!');
        }
    }

    public function executeTest($connection, $code)
    {
        $query =
            'BEGIN;';
        $query .=
            'SELECT plan(1);';
        $query .= 'SELECT lives_ok(';
        $query .= $code;
        $query .= '  );';
        $result = pg_query($connection, $query);

        $tes_result = '';

        if (!$result) {
            throw new \Exception($this->displayError(pg_last_error($connection)));
        } else {
            $tes_result = $result;
            pg_query($connection, 'rollback');
            return $tes_result;
        }
    }
    public function displayTestResult($tes_result)
    {
        $result = "<div id='output-text' class='w-100 font-weight-bold'> ";
        while ($row = pg_fetch_assoc($tes_result)) {
            if (strpos($row['runtests'], 'not ok') !== false) {
                $this->isAllowSubmit = false;
                $result .= "<div class='alert alert-danger'>";
                $result .= "<i class='fas fa-times'></i> " . $row['runtests'];
                $result .= "</div>";
            } else if (strpos($row['runtests'], 'failed') !== false) {
                $this->isAllowSubmit = false;
                $result .= "<div class='alert alert-warning'>";
                $result .= "<i class='fas fa-exclamation-triangle'></i> " . $row['runtests'];
                $result .= "</div>";
            } else {
                if ($this->isAllowSubmit == false) {
                    $this->isAllowSubmit = false;
                } else {
                    $this->isAllowSubmit = true;
                }
                $result .= "<div class='alert alert-success'>";
                $result .= "<i class='fas fa-check'> </i> " . $row['runtests'];
                $result .= "</div>";
            }
        }
        $result .= "</div>";
        return $result;
    }
    public function runtest(Request $request)
    {

        // $test = soal::where('id', '=', $request->soal_id)->get();
        // $test =DB::table('soal')
        // ->where('id', $request->soal_id)
        // ->get();
        // $this->code = $test[0]->code;

        // return response()->json(['result' => 'test']);


        // $this->jenis = $test[0]->jenis;
        // try {
        //     $mhs_connection = $this->connectToDatabase('pgtest');
        // } catch (\Exception $e) {
        //     return response()->json(['result' => $this->displayError($e->getMessage())]);
        // }
        //EXECUTE CODE
        // $code = $request -> code;


        // $connection = DB::connection('pgsql')->table('mahasiswa')->get();
        // return response()->json($connection);

        // $result = DB::connection('pgsql')->select(DB::raw(' BEGIN; SELECT plan(1); SELECT lives_ok ('.$code .')'));

        $conn = pg_connect("host = localhost port = 5432 dbname=pgtest user=postgres  password=hendra11");

        // $code = "INSERT INTO MAHASISWA VALUES ('hendra', '123','123'); ";

        $query = "BEGIN; ";
        $query .= strtolower ($request->code);
        $query .= " ROLLBACK;";

        $dml = "(update|delete|insert)"; 
        $code = strtolower ($request->code);
        
        $req = preg_match($dml,$code);
        $id = $request -> question_id;
        $dataresult = soal::where('id', $id)->first();

        //validasi value
        $codevalue = preg_replace('/\s+/', '', $code);
        $answervalue = preg_replace('/\s+/', '', $dataresult->jawaban);

        $cekvalue = null;
        $status = false;

        if ($codevalue == $answervalue){
            $cekvalue = ' value sesuai';
            $status = true;
        }else if ($codevalue != $answervalue){
            $status = false;
            $cekvalue =' value tidak sesuai';
        }

        if( $req == 1){
            try {
                pg_query($conn, $query);
                return response()->json(['query' => 'syntax berhasil dieksekusi', 'value'=>$cekvalue, 'status'=> $status]);
            } catch (\Exception $e) {
                $string = $e->getMessage();
                $prefix = "ERROR:";
                $index = strpos($string, $prefix) + strlen($prefix);
                $result = substr($string, $index);
    
                $finalResult = strstr($result, 'LINE 1:', true);
    
                if($finalResult == false){
                    return response()->json(['error' => 'undefined']);
                }else{
                    return response()->json(['error' => $finalResult]);
                }
            }
        } else{

            return response()->json(['error' => 'syntax error']);
        }


        


        // $query =
        //     'BEGIN;';
        // $query .=
        //     'SELECT plan(1);';
        // $query .= 'SELECT lives_ok(';
        // $query .="'". $code."'";
        // $query .= ' );';
        // $result = pg_query($connection, $query);


        // $tes_result = '';

        // if (!$result) {
        //     throw new \Exception($this->displayError(pg_last_error($connection)));
        // } else {
        //     $tes_result = $result;
        //     pg_query($connection, 'rollback');
        //     return $tes_result;
        // }
        
        // try {
        //     $finalResult = $this->displayTestResult($this->executeTest($connection, $this->code));
        // } catch (\Exception $e) {
        //     return response()->json(['result' => $this->displayError($e->getMessage())]);
        // }
        // return response()->json(['result' => $finalResult]);
    }
    public function submittest(Request $request)
    {

        $conn = pg_connect("host = localhost port = 5432 dbname=pgtest user=postgres  password=hendra11");
        $query = "BEGIN; ";
        $query .= strtolower($request->code) ;
        $query .= " ROLLBACK;";

        $dml = "(update|delete|insert)"; 
        $code = strtolower($request->code) ;
        
        $req = preg_match($dml,$code);
        $id = $request -> task_id;
        $dataresult = soal::where('id', $id)->first();

        //validasi value
        $codevalue = preg_replace('/\s+/', '', $code);
        $answervalue = preg_replace('/\s+/', '', $dataresult->jawaban);
        $cekvalue = null;
        $status = false;

        if ($codevalue == $answervalue){
            $cekvalue = ' value sesuai';
            $status = true;
        }else if ($codevalue != $answervalue){
            $status = false;
            $cekvalue =' value tidak sesuai';
        }

        $data = submission::where('soal_id', $request->task_id)->first();
        $soal = Soal::where('id', $request->task_id)->first();
        if( $req == 1){
            try {
                pg_query($conn, $query);
                
                if($status == true){

                    if(!$data){
                        $submission = submission::create([
                            'mahasiswa_id' => $request->user_id,
                            'soal_id' => $request->task_id,
                            'status' => 'passed',
                            'solution' => $soal->jawaban
                        ]);
                    }else{
                        $data->update([
                            'mahasiswa_id' => $request->user_id,
                            'soal_id' => $request->task_id,
                            'status' => 'passed',
                            'solution' => $soal->jawaban
                        ]);
                    }
                    return response()->json([ 'status' => 'passed', 'result' => 'syntax berhasil dieksekusi', 'message'=> 'Data berhasil disimpan !','value'=>$cekvalue, 'statuss'=> $status]);
                }else if($status == false){
                    if(!$data){
                        $submission = submission::create([
                            'mahasiswa_id' => $request->user_id,
                            'soal_id' => $request->task_id,
                            'status' => 'failed',
                            'solution' => $soal->jawaban
                        ]);
                    }else{
                        $data->update([
                            'mahasiswa_id' => $request->user_id,
                            'soal_id' => $request->task_id,
                            'status' => 'failed',
                            'solution' => $soal->jawaban
                        ]);
                    }
                    return response()->json(['error' => 'syntax error', 'message' =>'Data berhasil disimpan !']);
                }
                
                
            } catch (\Exception $e) {
                $string = $e->getMessage();
                $prefix = "ERROR:";
                $index = strpos($string, $prefix) + strlen($prefix);
                $result = substr($string, $index);
    
                $finalResult = strstr($result, 'LINE 1:', true);
    
                if(!$data){
                    $submission = submission::create([
                        'mahasiswa_id' => $request->user_id,
                        'soal_id' => $request->task_id,
                        'status' => 'failed',
                        'solution' => $soal->jawaban
                    ]);
                }else{
                    $data->update([
                        'mahasiswa_id' => $request->user_id,
                        'soal_id' => $request->task_id,
                        'status' => 'failed',
                        'solution' => $soal->jawaban
                    ]);
                }

                if($finalResult == false){
                    return response()->json(['error' => 'syntax error', 'message' => 'Data berhasil disimpan !']);
                }else{
                    return response()->json(['error' => $finalResult, 'message' => 'Data berhasil disimpan !']);
                }

            }
        } else{
            return response()->json(['error' => 'syntax error', 'message' =>'Data berhasil disimpan !']);
        }
    }
}
