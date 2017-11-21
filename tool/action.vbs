dim Url,FUrl,id,code,trade_type,number,trade_buy_price,trade_sell_price,objShell
On Error Resume Next 
Set objShell= CreateObject("wscript.shell")

do
call machining_price
  if id >0 then 
    call action
else
call countnumber
  end if
  objShell.SendKeys "{F4}"
WScript.Sleep 5000
  objShell.SendKeys "{F5}"
  Html=""  
Loop Until ac=1

function countnumber
Dim objHtmlDoc
    now_min=Minute(Now) mod 20
	'msgbox now_min
	if now_min=0 then
	Set objShell= CreateObject("wscript.shell")
    objShell.SendKeys "{down 4}"
    objShell.sendkeys "^C"
    Set objHtmlDoc = CreateObject("htmlfile")
    a=objHtmlDoc.parentWindow.clipboardData.GetData("text")
    MyString =Replace(a, "	", ",")
	MyArray = Split(MyString, ",", -1, 1)
	b=UBound(MyArray)
	b=b/17-1
	for i=1 to b
	code=MyArray(i*17)
	total_number= MyArray(i*17+2)/100
	useable_sell_number=MyArray(i*17+3)/100
	cost_price=MyArray(i*17+6)
	FUrl="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~hive_number~set~useable_sell_number="&useable_sell_number&",cost_price="&cost_price&",total_number="&total_number&"~where~code="&code&"~order~by~id~desc~limit~1"
    FHtml = getHTTPPageF(FUrl)
	next
	Set objHtmlDoc = Nothing
	end if
end function

function machining_price
a = int(rnd * 100000 + 1)
Url="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/getdate.php"&"?test="&a 
'msgbox Url
Html = getHTTPPage(Url)  
MyArray = Split(Html, ",", -1, 1)
      
  id=MyArray(0) '+Second(now)
  if id>0 then 
  'msgbox id
  code=MyArray(1)
  trade_type=MyArray(2)
  number=MyArray(3)
  trade_buy_price=MyArray(4)
  trade_sell_price=MyArray(5)
  end if
End Function

function getHTTPPage(Url)  
  dim Http  
  set Http=createobject("MSXML2.XMLHTTP")  
  Http.open "GET",Url,false  
  Http.send()  
  if Http.readystate  <> 4 then   
      exit function  
  end if  
  getHTTPPage=bytesToBSTR(Http.responseBody,"gb2312")  

  set http=nothing
  
  if err.number  <> 0 then err.Clear   
end function  


Function action
Const sTitle = "网上"   ' 查找窗口名称
Const nTimeOut = 5      ' 指定超时时间

Dim dtStart, bFind
dtStart = Now()
Do While DateDiff("s", dtStart, Now()) < nTimeOut
  WScript.Sleep 200
  If CreateObject("WScript.Shell").Appactivate(sTitle) Then
    bFind = True  ' 做标记-已找到
    Exit Do
  End If
Loop

'msgbox "22"
bFind = True
If bFind Then
  'msgbox "11"
  objShell.SendKeys "{F5}"
  ''msgbox "找到一个含有“百度”文字的窗口！耗时 " & DateDiff("s", dtStart, Now()) _
    '& " 秒。", vbSystemModal+vbInformation, WScript.ScriptName
if trade_type=5 or trade_type=6 or trade_type=7 or trade_type=8 or trade_type=9 or trade_type=12 then
objShell.SendKeys "{F1}"
WScript.Sleep 100
objShell.SendKeys "{down 4}"
WScript.Sleep 200
objShell.SendKeys "{Enter}"
WScript.Sleep 200
objShell.SendKeys code
WScript.Sleep 300
objShell.SendKeys "{down}"
WScript.Sleep 200
objShell.SendKeys trade_buy_price
WScript.Sleep 400
objShell.SendKeys "{Enter}"
WScript.Sleep 500
objShell.SendKeys number*100
WScript.Sleep 200
objShell.SendKeys "{B}"
WScript.Sleep 200
objShell.SendKeys "{Y}"
WScript.Sleep 300
objShell.SendKeys "{Enter}"
    do
	FUrl="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
	Loop Until FHtml=200
elseif trade_type<5 or trade_type=10 or trade_type=11 then
'msgbox "33"
objShell.SendKeys "{F2}"
WScript.Sleep 100
objShell.SendKeys "{down 4}"
WScript.Sleep 200
objShell.SendKeys "{Enter}"
WScript.Sleep 200
objShell.SendKeys code
WScript.Sleep 300
objShell.SendKeys "{down}"
WScript.Sleep 200
objShell.SendKeys trade_sell_price
WScript.Sleep 400
'objShell.SendKeys "{Enter}"
WScript.Sleep 500
objShell.SendKeys number*100
WScript.Sleep 300
objShell.SendKeys "{S}"
WScript.Sleep 200
objShell.SendKeys "{Y}"
WScript.Sleep 300
objShell.SendKeys "{Enter}"
    do
	FUrl="http://ju71-n2.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
    Loop Until FHtml=200

else
objShell.SendKeys "{F5}"
end if 
Else
 ' 'msgbox "找不到含有“百度”文字的窗口！耗时 " & DateDiff("s", dtStart, Now()) _
  '  & " 秒。", vbSystemModal+vbCritical, WScript.ScriptName
End If
''msgbox "结束"
End Function
            
function getHTTPPageF(FUrl) 
  dim Http  
  set Http=createobject("MSXML2.ServerXMLHTTP")  
  Http.open "GET",FUrl,false  
  Http.send()  
  if Http.readystate  <> 4 then   
      exit function  
  end if  
  getHTTPPageF=bytesToBSTR(Http.responseBody,"gb2312")  

  set http=nothing
  
  if err.number  <> 0 then err.Clear   
end function 

Function BytesToBstr(body,Cset)  
      dim objstream  
      set objstream = CreateObject("adodb.stream")  
      objstream.Type = 1  
      objstream.Mode =3  
      objstream.Open  
      objstream.Write body 
      objstream.Position = 0  
      objstream.Type = 2  
      objstream.Charset = Cset  
      BytesToBstr = objstream.ReadText   
      objstream.Close  
      set objstream = nothing  
End Function 
