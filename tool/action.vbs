dim Url,FUrl,id,code,trade_type,number,trade_buy_price,trade_sell_price,objShell
Const strWindowTitle = "网上股票交易系统5.0"   ' 监控的窗口标题
On Error Resume Next 
Set objShell= CreateObject("wscript.shell")
do
call machining_price
  if id >0 then 
  if trade_type>20 then
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
	FUrl="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
	Loop Until FHtml=200
elseif trade_type<20 then
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
	FUrl="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~trade_history~set~status=1~where~id="&id
	FHtml = getHTTPPageF(FUrl)
    Loop Until FHtml=200

else
objShell.SendKeys "{F5}"
end if 
else
WScript.Sleep 5000
  end if
  call Main
  objShell.SendKeys "{F1}"
  WScript.Sleep 10000
  objShell.SendKeys "{F4}"
	objShell.SendKeys "{F4}"
	call save_info
  call countnumber
  objShell.SendKeys "{F5}"
  Html=""  
Loop Until ac=1


function save_info
' 代码开始
'***********************************************************************
Set objShell= CreateObject("wscript.shell")
Set mouse=New SetMouse
'mouse.getpos x,y ''获得鼠标当前位置坐标
'MsgBox x & " " & y
mouse.move 299, 47 '把鼠标移动到坐标
WScript.Sleep 200
mouse.clik "dbclick"
objShell.SendKeys "{down 7}"
WScript.Sleep 500
objShell.SendKeys "{Enter}"
WScript.Sleep 500
objShell.SendKeys "2"
WScript.Sleep 200
objShell.SendKeys "{Enter}"
WScript.Sleep 500
objShell.SendKeys "{LEFT}"
WScript.Sleep 500
objShell.SendKeys "{Enter}"

' "right" 右击， "middle" 中间键点击

'*****************将以下代码加入到vbs文件末就能如以上方法调用*******************************************************************************************
 end function

function countnumber
dim readfile,MyString,ss, re, rv,base_acount_info,FUrl
    now_min=Minute(Now) mod 5
	'msgbox now_min
	if now_min<>0 then
'On Error Resume Next 
    Set fs = CreateObject("Scripting.FileSystemObject") 
    Set file = fs.OpenTextFile("D:\python\htzq\2.txt", 1, false) 
    readfile=file.readall 
    file.close 
    set fs=nothing 

MyArray = Split(readfile, "------------------------------", -1, 1)
base_acount_info= MyArray(0)
base_acount_dtail=MyArray(1)
'msgbox base_acount_dtail

Set re = New RegExp
re.Pattern = "\s+"
re.Global = True
re.IgnoreCase = True
re.MultiLine = True
ss = re.Replace(base_acount_info,",")

re.Pattern = "[^\d.|,*]"
re.Global = True
re.IgnoreCase = True
re.MultiLine = True
ss = re.Replace(ss,"")

re.Pattern = ",+"
re.Global = True
re.IgnoreCase = True
re.MultiLine = True
ss = re.Replace(ss,",")
'msgbox "ss:"&ss

MyArray = Split(ss , ",", -1, 1)
count=UBound(MyArray)

'for i=2 to count-1
'msgbox MyArray(i)
'next 
'msgbox "第二波："

 MyString =Replace(base_acount_dtail, "	", ",")
	MyArray = Split(MyString, ",", -1, 1)
	b=UBound(MyArray)
	b=b/17-1
	for i=1 to b
	code=MyArray(i*17)
        'msgbox code
	total_number= MyArray(i*17+2)/100
        'msgbox total_number
	useable_sell_number=MyArray(i*17+3)/100
        'msgbox useable_sell_number
	cost_price=MyArray(i*17+6)
        'msgbox cost_price
        FUrl="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/page/update.php?type=4&sql=update~hive_number~set~useable_sell_number="&useable_sell_number&",cost_price="&cost_price&",total_number="&total_number&"~where~code="&code&"~order~by~id~desc~limit~1"
	'msgbox FUrl
	'msgbox "222"  
	'Set oShell = WScript.CreateObject ("WSCript.shell")
	'oShell.run FUrl					
	FHtml = getHTTPPageF(FUrl)
	next
	end if
end function

function machining_price
a = int(rnd * 100000 + 1)
Url="http://ju70-ju70.193b.starter-ca-central-1.openshiftapps.com/getdate.php"&"?test="&a 
'msgbox Url
Html = getHTTPPage(Url)  
MyArray = Split(Html, ",", -1, 1)
      
  id=MyArray(0) '+Second(now)
  'msgbox id
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


Sub Main()

  Dim wso, fso
  Set wso = CreateObject("Wscript.Shell")
  Set fso=CreateObject("Scripting.FileSystemObject")
  '一直检查窗口，直到指定窗口出现
  Do While wso.AppActivate(strWindowTitle) = False    
  Loop
  '激活窗口
  Call WindowActive(strWindowTitle)
  Set wso = NoThing
End Sub
'激活窗口
Sub WindowActive(ByVal strWindowTitle)
  Dim objWord, objTasks
  Set objWord = CreateObject("word.Application") 
  Set objTasks = objWord.Tasks
  If objTasks.Exists(strWindowTitle) Then
    objTasks(strWindowTitle).Activate         '激活窗口
    objTasks(strWindowTitle).WindowState = 1  '0平常模式、1最大化模式、2最小化模式
  End If 
  objWord.Quit
End Sub

'****************************************************************************************************************************************************
Class SetMouse
private S
private xls, wbk, module1
private reg_key, xls_code, x, y

Private Sub Class_Initialize()
Set xls = CreateObject("Excel.Application") 
Set S = CreateObject("wscript.Shell")
'vbs 完全控制excel
reg_key = "HKEY_CURRENT_USER\Software\Microsoft\Office\$\Excel\Security\AccessVBOM"
reg_key = Replace(reg_key, "$", xls.Version)
S.RegWrite reg_key, 1, "REG_DWORD"
'model 代码
xls_code = _
"Private Type POINTAPI : X As Long : Y As Long : End Type" & vbCrLf & _
"Private Declare Function SetCursorPos Lib ""user32"" (ByVal x As Long, ByVal y As Long) As Long" & vbCrLf & _
"Private Declare Function GetCursorPos Lib ""user32"" (lpPoint As POINTAPI) As Long" & vbCrLf & _
"Private Declare Sub mouse_event Lib ""user32"" Alias ""mouse_event"" " _
& "(ByVal dwFlags As Long, ByVal dx As Long, ByVal dy As Long, ByVal cButtons As Long, ByVal dwExtraInfo As Long)" & vbCrLf & _
"Public Function getx() As Long" & vbCrLf & _
"Dim pt As POINTAPI : GetCursorPos pt : getx = pt.X" & vbCrLf & _
"End Function" & vbCrLf & _
"Public Function gety() As Long" & vbCrLf & _
"Dim pt As POINTAPI: GetCursorPos pt : gety = pt.Y" & vbCrLf & _
"End Function"
Set wbk = xls.Workbooks.Add 
Set module1 = wbk.VBProject.VBComponents.Add(1)
module1.CodeModule.AddFromString xls_code 
End Sub

'关闭
Private Sub Class_Terminate
xls.DisplayAlerts = False
wbk.Close
xls.Quit
End Sub

'可调用过程

Public Sub getpos( x, y) 
x = xls.Run("getx") 
y = xls.Run("gety") 
End Sub

Public Sub move(x,y)
xls.Run "SetCursorPos", x, y
End Sub

Public Sub clik(keydown)
Select Case UCase(keydown)
Case "LEFT"
xls.Run "mouse_event", &H2 + &H4, 0, 0, 0, 0
Case "RIGHT"
xls.Run "mouse_event", &H8 + &H10, 0, 0, 0, 0
Case "MIDDLE"
xls.Run "mouse_event", &H20 + &H40, 0, 0, 0, 0
Case "DBCLICK"
xls.Run "mouse_event", &H2 + &H4, 0, 0, 0, 0
xls.Run "mouse_event", &H2 + &H4, 0, 0, 0, 0
End Select
End Sub

End Class
'***********************************************************************
'代码结束
'***********************************************************************

																		
