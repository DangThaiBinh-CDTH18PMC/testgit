<?php 
$arr_search_keyword = "soi kèo, kèo nhà cái, nhận định bóng đá, nhan dinh bong da, soi kèo bóng đá, dự đoán bóng đá, du doan bong da";
$arr_search_keyword_arr = explode(', ', $arr_search_keyword);
$arr_keyword_result = [];

foreach($arr_search_keyword_arr as $search_keyword){
  $search_keyword_re = str_replace(' ','%20',$search_keyword);
  $url = 'http://ahrefs7.tool.buyseotools.io/positions-explorer/organic-keywords/v5/subdomains/vn/all/all/all/all/all/all/all/1/traffic_desc?target=tipkimcuong.com&include='.$search_keyword_re.'&inc-criteria=or&search-in=keyword%2Curl&search-in-criteria=or';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36',
        'Cookie: buyseotools=6d3a024b333be0edd0d8ea58ee0cd702; _ga=GA1.2.1397160334.1606837059; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1'
      ),
    ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    curl_close($curl);
    $match = "\$\(\\'body\\'\)\.data\(document\.URL\,([^\$]+)?";
    preg_match('/\.data\(document\.URL\,([^\$]+)?/', $response, $output_array);
    $keyword = preg_replace('/","keyword_id":([^\,]+)?\,/', ',', $output_array[1]);
    $str =  str_replace('{"result":[', '', $keyword);
    $json =  str_replace("loadAJAX('1');", '', $str);
    $arr = explode('"keyword":"', $json);
    foreach ($arr as $key => $value) {
      if($value!=' {'){
        $str = preg_replace('/\"traffic\"(.*)/', '', $value);
        $arr_kq = explode(',"', $str);
        $url = '';
        foreach ($arr_kq as $key => $item) {
          if (strpos($item, 'url":"') !== false) {
            $url = $item;
          }
        }
        preg_match('/url\"\:\"([^\"]+)?/', $url, $output_array);
        $url = $output_array[1];
        $cpc = '';
        foreach ($arr_kq as $key => $item) {
          if (strpos($item, 'cpc":"') !== false) {
            $cpc = $item;
          }
        }
        preg_match('/cpc\"\:\"([^\"]+)?/', $cpc, $output_array);
        $cpc = $output_array[1];
        $volume = '';
        foreach ($arr_kq as $key => $item) {
          if (strpos($item, 'volume":"') !== false) {
            $volume = $item;
          }
        }
        preg_match('/volume\"\:\"([^\"]+)?/', $volume, $output_array);
        $volume = $output_array[1];
        $keyword = ($arr_kq[0]);
        $keyword =  json_decode('"'.$keyword.'"');
    
        $position = $arr_kq[1];
        $position = str_replace('position":', '', $position);
        $position_history = $arr_kq[2];
        $ketquatanggiam = '';
        $position_history = str_replace('position_history":', '', $position_history);
        if(((int) $position_history -  (int) $position) > 0){
          $ketquatanggiam = '+ '.($position_history - $position);
        }else if($position_history=='""'){
          $ketquatanggiam = '0';
        }else{
          $ketquatanggiam = ''.($position_history - $position);
        }
        // lay du lieu can theo doi va check ket qua
        if ($keyword == $search_keyword) {
          $arr_keyword = [];
          $arr_keyword['position'] = $position;
          $arr_keyword['keyword'] = $keyword;
          $arr_keyword['ketquatanggiam'] = $ketquatanggiam;
          $arr_keyword['volume'] = $volume;
          array_push($arr_keyword_result,$arr_keyword);
        }
      }
    }
    
}
$arr_keyword_results[] =  '<b>THEO DÕI TỪ KHÓA :</b>'."\n\r";
  if(count($arr_search_keyword_arr)):
    foreach ($arr_search_keyword_arr as $key => $value) {
      if(count($arr_keyword_result)>0):
        $colors = array_column($arr_keyword_result, 'keyword');
        $found_key = array_search($value, $colors);
          if($arr_keyword_result[$found_key]['keyword']==$value){
              $arr_keyword_results[] = ''.$value .' | '.$arr_keyword_result[$found_key]['position'].' | '.$arr_keyword_result[$found_key]['ketquatanggiam'].' | '.$arr_keyword_result[$found_key]['volume']."\n\r";
          }else{
            $arr_keyword_results[] =  ''.$value .' | N/A'."\n\r";
          }
        
      else:
        $arr_keyword_results[] =  ''.$value .' | N/A'."\n\r";
      endif;
    }
  endif;
  print_r($arr_keyword_results);
			 $message =  implode('',$arr_keyword_results);
			 $channel = -1001479900997;
			 $token = '1437357371:AAHhmJmMl8pI6JYqE43hqn7UkMcSHryemlI';
			 $data = [
			     'text' => $message,
			     'chat_id' => $channel,
			     'parse_mode'=>'html'
			 ];
			 //@file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data)) ;
