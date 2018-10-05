<?php
class Decide{
  public $trade_buy_score;
	
  function __construct() {
	//获取code，table_name配置信息
	$this->Runner=new Runner();
	$this->table_name=$this->Runner->get_config()['table_name']; 
	$this->code=$this->Runner->get_config()['code'];	
	//获取db操作信息
	$this->db = new db();  
	//初始化log对象  
	$this->log = new logs();
	//获取实时数据  
	$this->MachiningPrice= new MachiningPrice();   
	//测试代码，测试方法调用  
}
  public function get_15kdjbuy_score(){
  $this->log->log_work("comming get_kdj_score");	  
  $kdj = new Kdj(); 	  
  $kdj_data=$kdj->get_kdj();
	  
     if($kdj_data[min15_k]>=90 or $kdj_data[min15_d]>=85){
	  $trade_buy_score=0;    
          echo "lalal15>80~$trade_buy_score\n";
      }	  
	  
      if($kdj_data[min15_k]>=80 and $kdj_data[min15_k]<90){
	  $trade_buy_score=2;    
          echo "lalal15>80~$trade_buy_score\n";
      }
      if($kdj_data[min15_k]>=60 and $kdj_data[min15_k]<80){
	  $trade_buy_score=4;    
	 echo "lalal15>60~$trade_buy_score\n";
      }
       if($kdj_data[min15_k]>=20 and $kdj_data[min15_k]<60){
	   $trade_buy_score=6;    
	   echo "lalal15>60~$trade_buy_score\n";
      }	
	  
       if($kdj_data[min15_k]>=10 or $kdj_data[min15_k]<20){
	   $trade_buy_score=8;    
	   echo "lalal15>60~$trade_buy_score\n";
      }	

      if($kdj_data[min15_k]<10 or $kdj_data[min15_d]<10){
  	 $trade_buy_score=10;    
   	 echo "lalal15>60~$trade_buy_score\n";
      }
	return $trade_buy_score;   
  }
  
  public function get_30kdjbuy_score(){
  $this->log->log_work("comming get_kdj_score");	  
  $kdj = new Kdj(); 	  
  $kdj_data=$kdj->get_kdj();
	  
     if($kdj_data[min30_k]>=90 or $kdj_data[min30_d]>=85){
	  $trade_buy_score=0;    
          echo "lalal30>80~$trade_buy_score\n";
      }	  
	  
      if($kdj_data[min30_k]>=80 and $kdj_data[min30_k]<90){
	  $trade_buy_score=2;    
          echo "lalal30>80~$trade_buy_score\n";
      }
      if($kdj_data[min30_k]>=60 and $kdj_data[min30_k]<80){
	  $trade_buy_score=4;    
	 echo "lalal30>60~$trade_buy_score\n";
      }
       if($kdj_data[min30_k]>=20 and $kdj_data[min30_k]<60){
	   $trade_buy_score=6;    
	   echo "lalal30>60~$trade_buy_score\n";
      }	
	  
       if($kdj_data[min30_k]>=10 or $kdj_data[min30_k]<20){
	   $trade_buy_score=8;    
	   echo "lalal30>60~$trade_buy_score\n";
      }	

      if($kdj_data[min30_k]<10 or $kdj_data[min30_d]<10){
  	 $trade_buy_score=10;    
   	 echo "lalal30>60~$trade_buy_score\n";
      }
	  return $trade_buy_score; 
  }
	
  public function get_60kdjbuy_score(){
  $this->log->log_work("comming get_kdj_score");	  
  $kdj = new Kdj(); 	  
  $kdj_data=$kdj->get_kdj();
	  
     if($kdj_data[min60_k]>=90 or $kdj_data[min60_d]>=85){
	  $trade_buy_score=0;    
          echo "lalal60>80~$trade_buy_score\n";
      }	  
	  
      if($kdj_data[min60_k]>=80 and $kdj_data[min60_k]<90){
	  $trade_buy_score=2;    
          echo "lalal60>80~$trade_buy_score\n";
      }
      if($kdj_data[min60_k]>=60 and $kdj_data[min60_k]<80){
	  $trade_buy_score=4;    
	 echo "lalal60>60~$trade_buy_score\n";
      }
       if($kdj_data[min60_k]>=20 and $kdj_data[min60_k]<60){
	   $trade_buy_score=6;    
	   echo "lalal60>60~$trade_buy_score\n";
      }	
	  
       if($kdj_data[min60_k]>=10 or $kdj_data[min60_k]<20){
	   $trade_buy_score=8;    
	   echo "lalal60>60~$trade_buy_score\n";
      }	

      if($kdj_data[min60_k]<10 or $kdj_data[min60_d]<10){
  	 $trade_buy_score=10;    
   	 echo "lalal60>60~$trade_buy_score\n";
      }	
	  return $trade_buy_score; 
  }	
  
  public function get_120kdjbuy_score(){
  $this->log->log_work("comming get_kdj_score");	  
  $kdj = new Kdj(); 	  
  $kdj_data=$kdj->get_kdj();
	  
     if($kdj_data[min120_k]>=90 or $kdj_data[min120_d]>=85){
	  $trade_buy_score=0;    
          echo "lalal120>80~$trade_buy_score\n";
      }	  
	  
      if($kdj_data[min120_k]>=80 and $kdj_data[min120_k]<90){
	  $trade_buy_score=2;    
          echo "lalal120>80~$trade_buy_score\n";
      }
      if($kdj_data[min120_k]>=60 and $kdj_data[min120_k]<80){
	  $trade_buy_score=4;    
	 echo "lalal120>60~$trade_buy_score\n";
      }
       if($kdj_data[min120_k]>=20 and $kdj_data[min120_k]<60){
	   $trade_buy_score=6;    
	   echo "lalal120>60~$trade_buy_score\n";
      }	
	  
       if($kdj_data[min120_k]>=10 or $kdj_data[min120_k]<20){
	   $trade_buy_score=8;    
	   echo "lalal120>60~$trade_buy_score\n";
      }	

      if($kdj_data[min120_k]<10 or $kdj_data[min120_d]<10){
  	 $trade_buy_score=10;    
   	 echo "lalal120>60~$trade_buy_score\n";
      }	
	  return $trade_buy_score; 
  }
 
  public function get_daykdjbuy_score(){
  $this->log->log_work("comming get_kdj_score");	  
  $kdj = new Kdj(); 	  
  $kdj_data=$kdj->get_kdj();
	  
     if($kdj_data[kdjday_k]>=90 or $kdj_data[kdjday_d]>=85){
	  $trade_buy_score=0;    
          echo "lalalday>80~$trade_buy_score\n";
      }	  
	  
      if($kdj_data[kdjday_k]>=80 and $kdj_data[kdjday_k]<90){
	  $trade_buy_score=2;    
          echo "lalalday>80~$trade_buy_score\n";
      }
      if($kdj_data[kdjday_k]>=60 and $kdj_data[kdjday_k]<80){
	  $trade_buy_score=4;    
	 echo "lalalday>60~$trade_buy_score\n";
      }
       if($kdj_data[kdjday_k]>=20 and $kdj_data[kdjday_k]<60){
	   $trade_buy_score=6;    
	   echo "lalalday>60~$trade_buy_score\n";
      }	
	  
       if($kdj_data[kdjday_k]>=10 or $kdj_data[kdjday_k]<20){
	   $trade_buy_score=8;    
	   echo "lalalday>60~$trade_buy_score\n";
      }	

      if($kdj_data[kdjday_k]<10 or $kdj_data[kdjday_k]<10){
  	 $trade_buy_score=10;    
   	 echo "lalalday>60~$trade_buy_score\n";
      }
	return $trade_buy_score; 
  }
	public get_status(){
	$data=$this->MachiningPrice->get_machining_price();
	$row=$this->db->get_select("select * from hive_number where code=$this->code and stat_date<'$data[stat_date]' order by id desc limit 1;");
	    $data['switched'] = $row[switched];  //总开关
	    $data['sell_switched'] = $row[sell_switched];  //总开关
            $data['return_switched'] = $row[return_switched];  //总开关
	    $data['buy_switched'] = $row[buy_switched];  //总开关
	    echo "test";
	    print_r($data);
    
	}
	
   public function get_daypoint_score(){
    $this->log->log_work("comming daypoint");
    $sql="select sum(make_bite) from day_point order by stat_date desc limit 5;";	  
    $row=$this->db->get_select($sql);
    if($row[0]>=2){
       $trade_buy_score=10;	    
    }
    if($row[0]>=0 and $row[0]<2){
       $trade_buy_score=8;	    
    }	
    if($row[0]>=-2 and $row[0]<0){
       $trade_buy_score=4;	    
    }
     if($row[0]<-2){
       $trade_buy_score=0;	    
    }
    return $trade_buy_score; 	   
  }	
	
  public function main(){
      $minkdj15_score=$this->get_15kdjbuy_score();	
      $minkdj30_score=$this->get_30kdjbuy_score();
	$minkdj60_score=$this->get_60kdjbuy_score();	
	$minkdj120_score=$this->get_120kdjbuy_score();
	$daykdj_score=$this->get_daykdjbuy_score();
	$daypoint_score=$this->get_daypoint_score();
	$trade_buy_score=($minkdj15_score+$minkdj30_score+$minkdj60_score+$minkdj120_score+$daykdj_score+$daypoint_score)/6;
	echo "trade_buy_score:$trade_buy_score";
     if($trade_buy_score>=8){
      $action_buy_degree=4;
      }
      if($trade_buy_score>=5 and $trade_buy_score<8){
      $action_buy_degree=2;
      }
      if($trade_buy_score>=0 and $trade_buy_score<5){
      $action_buy_degree=0;
      }  
  return $action_buy_degree;   //获得kdj综合得分；
  }		
}
?>
