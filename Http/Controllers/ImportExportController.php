<?php

namespace Sanjok\Blog\Http\Controllers;

class ImportExportController extends BaseController
{
    public function uploadcsv(Request $request, $cid)
    {
        if ($request->employee_csv =='') {
            return back()->withErrors(trans('message.csv_must'));
        }
        $extention = ['csv', 'xlsx', 'xls'];
        $origanlExt = $request->file('employee_csv')->getClientOriginalExtension();
        if (in_array($origanlExt, $extention)) {
            $path =$request->file('employee_csv')->getRealPath();
            $usrarray = $this->csvToArray($path);

            $existing_email = $message = '';
            foreach ($usrarray as $user) {
                if (User::where('email', '=', $user['Email'])->exists()) {
                    $existing_email.= $user['Email'].' , ';
                } else {
                    $userinfo = array(
                        'name'   => $user['Name'],
                        'email'   => $user['Email'],
                        'roles'   => 'trainee'
                    );

                    if ($user = $this->userService->createUser($request, $userinfo)) {
                        // dd("here");
                        //create coach profile
                        $user->id;
                        Employee::create([
                            'user_id' => $user->id,
                            'company_id' => $cid,
                            'fullname' => $userinfo['name'],
                        ]);
                    }
                }
            }

            if ($existing_email!='') {
                $message = 'Email id with '. $existing_email.' already existed';
            }
            $route = route('admin.employee.index', $cid);
            return redirect($route)->with('message', trans('message.employee_imported') .$message);
        } else {
            return back()->withErrors(trans('message.format_not_supported'));
            echo "";
        }
    }

    /*
     * convert csv field to array
     *
     * @return $data
     */
    public function csvToArray($file, $delimiter = ',')
    {
        $header = null;
        $data = array();
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
