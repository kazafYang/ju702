<?php
/*
vifi_status 核销状态
cut_price 悲观
target_price  目标
connecttion_id  联系id

有2个方案，要么废掉卖出条件，全部接入追；有新思路等等

理想状态下：达到sell的时候，我每次去遍历一把order_list 看是否有符合条件的怎么样呢？有我就操作，没有我就过滤掉这个信号，避免卖飞了没有好处呢？
悲观状态下：我就砍自己一刀，还是补一刀进去？补一刀就跟目前状态一样了？砍还是补？越补越套，砍了怎么办。不买了？补，怎么补无止尽的补下去吗，这里肯定有
个限度的，补几次
方案是每次操作几只，资金量不足，每次就只能操作几只；这样每次我就只投入固定的数量进去了；补的话就可以再判断一下，是否具备条件，多久补一次，这样就解决了
为什么要砍？那是因为遇到高位，还买入了。所以回调发生风险了，那我不在高位买嘛，下来幅度也有限，借助下一次反弹，用理想状态干掉这个？

涉及到卖出的我都去遍历一遍order_list表有没有前期的订单，可以直接卖的

vifi_status=0 and begin_price>=target_price and 

要有一个字段随时进行更新，找最大值的
max
begin
min
*/
funcation test(){
if($trade_min15_k>=85 or $trade_min15_d>=80){	    
$sql = "insert into trade_history (id,code,stat_date,stat_time_hour,stat_time_min,status,number,trade_type,trade_buy_price,trade_sell_price,vifi_status,) values ('$trade_id','$trade_code','$trade_stat_date','$trade_time_hour','$trade_time_min','0','$number','1','$trade_buy_price','$trade_sell_price');";                                                                  


}
}

?>
