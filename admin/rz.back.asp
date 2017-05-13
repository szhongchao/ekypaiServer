<!--#include file="md5.asp"-->

<%
'------------------------------------
 
uid=replace(trim(request("uid")),"'","")
pwd=replace(trim(request("pwd")),"'","")
 
fileid=lcase(replace(request ("pid"),"'",""))

'-------------------------------------------------------------------
ver= request("v") 


'--------------------------------------------------------------------

 dim tmpuid
tmpuid=""
if left(uid,3)="|||" then

  uid=right(uid,len(uid)-3)

  arr=split(uid,"|||")
  
  for i=0 to ubound(arr)
  
     tmpuid=tmpuid & chrw(arr(i))
  
  next
  
  uid=tmpuid
 

end if





 
 pass=false


 code=request("code") 
 
                                                     
response.write "AAAAAA" & "775BA57F13190638175B156A6619034D774B100F36097618" & "|" & "0" & "|" & "" & "|" & "danwei"   & "|" & md5(code)  

%>
