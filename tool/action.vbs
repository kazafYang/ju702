dim Url,new_price,update_time

Url="http://test.ju70.com/getdate.php" 

call machining_price

msgbox new_price

msgbox update_time

function machining_price
Html = getHTTPPage(Url)  
MyArray = Split(Html, ";", -1, 1) 
new_price=MyArray(0) '+Second(now)
update_time=MyArray(1)

MyArray = Split(Html, ";", -1, 1) 
new_price=MyArray(0) '+Second(now)
update_time=MyArray(1)

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
