

### 安装地址 composer require lims/curl dev-master
### Curl类 此插件主要提供curl post  get方法
    //引用事例
    public $lphpexcel,$curl;
    public function __construct()
    {
        $this->lphpexcel=new LPhpExcel();
    }

    public function index()
    {
        $this->lphpexcel->explode_data('数据','数据',['id','name'],[[1,'lims'],[2,'jinlu']]);
    }
    public function implode_data()
    {
        $data=$this->lphpexcel->importExecl("/web/tp5/public/sj.xls");
        print_r($data);die;
    }
# 代码解释
    /**
     * get
     *
     * @return array
     */
    public function get($url, $userAgent = null, $header = 0)
    {
        $this->userAgent = $userAgent ? $userAgent : $this->userAgent;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        $ret['data'] = curl_exec($curl);
        $ret['info'] = curl_getinfo($curl);
        curl_close($curl);
        return $ret;
    }

    /**
     * post
     *
     * @return array
     */
    public function post($url, $param = null, $userAgent = null, $header = 0)
    {
        $this->userAgent = $userAgent ? $userAgent : $this->userAgent;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        if ($param != null) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }
        $ret['data'] = curl_exec($curl);
        $ret['info'] = curl_getinfo($curl);
        curl_close($curl);
        return $ret;
    }
# LPhpExcel类主要提供两个导入导出方法
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