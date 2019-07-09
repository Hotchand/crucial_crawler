<?php
	//download csv file
	if(!function_exists('icecat_store_csv')){
	function icecat_store_csv($filename, $data)
	{
		$fp = fopen($filename, 'w');
		foreach ($data as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		return TRUE;
	}
	}
	
	
	$url[0]['url_data'] = "http://www.crucial.com/usa/en/compatible-upgrade-for/Supermicro/1012A-M73RF";
	$url[1]['url_data'] = "http://www.crucial.com/usa/en/compatible-upgrade-for/Supermicro/2027GR-TRF";
	$url[2]['url_data'] = "http://www.crucial.com/usa/en/compatible-upgrade-for/Supermicro/2027TR-H70RF-";
	$url[3]['url_data'] = "http://www.crucial.com/usa/en/compatible-upgrade-for/Supermicro/SuperServer-1017GR-TF-FM109";
	//$content = file_get_contents($url);
     $a = 0;
	 //$crucial_data[$a][0] = array('title','mpn','description','model','price','image');
     $dom = new DOMDocument;
     libxml_use_internal_errors(true);  //hides errors from invalid tags
     //foreach($url as $url1)
	   //debug_array($csv->data);exit;
	
		foreach($url as $data_key => $data_val)
		{
		if($data_key == 'url_data')	{
		 $dom->loadHTMLFile($data_val);
		 $DOMxpath = new DOMXPath($dom);
	  
		  
		 $main_div = $DOMxpath->query("//div[@class='product-module']");	
		 
		 foreach($main_div as $md)
		 {     
			 // get product description
			 $nodes  = $DOMxpath->query("//div[@class='product-module-desc']",$md);		 
			 $i=1;
			 foreach($nodes as $node)
			 {
				 $h3 = $DOMxpath->query("h3", $node);
				 $h4 = $DOMxpath->query("h4", $node);
				 $ul = $DOMxpath->query("ul", $node);
				 $prod_url = '';
				 $crucial_data[$a][$i]['title'] =  trim($h3->item(0)->nodeValue); 
				 $crucial_data[$a][$i]['mpn'] =  trim($h4->item(0)->nodeValue); 		 
				
				foreach($ul as $ul_tags){
					$li = $DOMxpath->query("li", $ul_tags);
					$crucial_data[$a][$i]['description'] = trim($li->item(0)->nodeValue); 
					$crucial_data[$a][$i]['model'] = trim($li->item(1)->nodeValue); 			
					//debug_array($li);
				}
				$i++;					
			 }
			  // get product price
			 $price_box  = $DOMxpath->query("//div[@class='cart-options']/div[@class='price']/ul[@class='priceList']/li",$md);
			 $j = 1;
			 foreach($price_box as $c_price){
				 $price = trim($c_price->nodeValue);
				  $crucial_data[$a][$j]['price'] = substr($price,1);
				  $j++;
			 }
			 // get product image
			 $k = 1;
			 $img_box =$DOMxpath->query("//div[@class='box']/a/img",$md);
			 foreach($img_box as $img){
				 $image = str_replace('thumbnail','large',$img->getAttribute("src"));
				 //echo '<img src="'.$image.'">';
			   $crucial_data[$a][$k]['image'] =  trim($image);
			   $k++;
			 }
		 }
		 $a++;
	  }
	 }
	
	 
	 //save data to csv
	 $c_data[0] = array('title','mpn','description','model','price','image');
	 $b = 1;
	  foreach($crucial_data as $crucial_data1){
		  foreach($crucial_data1 as $crucial_data2)
		  {
			 //debug_array($crucial_data2);
			 foreach($crucial_data2 as $key => $val){
			   $c_data[$b][$key] = $val;
			 }
			 $b++;
		  }
	  }
	 
	 //debug_array($c_data);
	 $filename = 'crucial_data_'.time().'.csv';
	 icecat_store_csv($filename, $c_data);
	 
	

?>
