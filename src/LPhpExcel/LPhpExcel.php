<?php
/**
 * 处理phpexcel导出数据
 */
namespace src\LPhpExcel;

class LPhpExcel{
    //定义基础变量
    protected $phpexcel;
    public function __construct()
    {
        $this->phpexcel=new \PHPExcel();
    }

    /**
     * 导出数据方法
     * @param $file_name            - 文件名称
     * @param $sheet_title          - sheet名称
     * @param array $date_title     - 列头设置
     * @param array $data           - 数据
     */
    public function explode_data($file_name,$sheet_title,array $data_title,array $data)
    {
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        //填充列头信息
        for ($i = 0; $i < count($data_title); $i++) {
            $this->phpexcel->getActiveSheet()->setCellValue("$letter[$i]".'1', $data_title[$i]);
        }

        for ($i = 2; $i <= count($data) + 1; $i++)
        {
            $j = 0;
            foreach ($data[$i - 2] as $key => $value)
            {
                $this->phpexcel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
                $j++;
            }
        }

        $this->phpexcel->getActiveSheet()->settitle($sheet_title);
        $write = new \PHPExcel_Writer_Excel5($this->phpexcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$file_name.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }


    /**
     * 数据导入
     * @param string $file excel文件
     */
    function importExecl($file=''){
        $file = iconv("utf-8", "gb2312", $file);   //转码
        if(empty($file) OR !file_exists($file)) {
            die('file not exists!');
        }
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($file,$encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $data=[];
        for($i=1;$i<$highestRow+1;$i++){
            foreach($letter as $k=>$v){
                if($v==$highestColumn){
                    $data[$i][$k] = $objPHPExcel->getActiveSheet()->getCell($v.$i)->getValue();
                    break;
                }
                $data[$i][$k] = $objPHPExcel->getActiveSheet()->getCell($v.$i)->getValue();
            }
        }
        return $data;
    }
}