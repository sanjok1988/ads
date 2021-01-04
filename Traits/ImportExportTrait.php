<?php

namespace Sanjok\Blog\Traits;

use Illuminate\Http\Request;

trait ImportExportTrait
{


    /**
     * Undocumented function
     *
     * @param Request $request
     * @param array $cols column selection of columns from csv
     * @return void
     */
    public function importCsv(Request $request, $cols = [])
    {
        if ($request->_csv == '') {
            return back()->withErrors(trans('message.csv_must'));
        }
        //dd($request->_csv);
        //dd($request->file('_csv'));
        $cols = ['fullname', 'email', 'address', 'phone', 'photo', 'designation', 'content', 'linkedin', 'fb', 'insta'];

        $extention = ['csv', 'xlsx', 'xls'];
        $origanlExt = $request->file('_csv')->getClientOriginalExtension();
        if (in_array($origanlExt, $extention)) {
            $path = $request->file('_csv')->getRealPath();
            $array = $this->csvToArray($path);

            $result = $this->only($cols, $array);

            return $result;
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
