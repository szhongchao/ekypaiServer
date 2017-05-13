
<!--#include file="../lic/md5.asp"-->

<%
'------------------------------------
dim mediaid,licselect,uid,pwd,vbnum,sql,arr,k
 
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


 
'--------------------------------------------------------------------
 
dim fee,drmdot,drmstr,usedlicnum,filearr,fid,pass
dim licplaycount
dim licplaytime
dim licenddate
dim licplaytimeafteruse
dim liccd
dim liccdnumbers
dim licdel
dim lictosdmi
dim lictononsdmi
dim lictosdmicount
dim licsdmienddate
dim pcstr,pcnum


 
 pass=false

      '测试读取MySql数据库的内容
        ConnStr="driver={mysql odbc 5.1 driver};server=localhost;database=kypai;uid=kypai;password=szc743712;"

      Set conn = Server.CreateObject("ADODB.Connection")
         conn.Open ConnStr
      set rs=Server.CreateObject("ADODB.recordset")
      set temprs=Server.CreateObject("ADODB.recordset")
       rs.Open"select top 1 * from zp_userinfo where uid='"& uid &"' ",conn,0,1
     '   rs.Open"select top 1 * from drm_userinfo where uid='"& uid &"' and (pwd='"& md5(pwd&drmkey) &"' or pwd='"& pwd &"')",conn,0,1
     if rs.EOF then
        rs.Close:set rs=nothing
        conn.Close:set conn=nothing
         response.Write("Error:激活码错误 ")
        response.End 
     end if
     if isnull(rs("drmsts")) or rs("drmsts")<>1 then
        rs.Close:set rs=nothing
        conn.Close:set conn=nothing
        response.Write("Error:该用户目前是禁用状态！")
        response.End 
     end if
     if not isnull(rs("drmenddate")) then
     if date()>cdate(rs("drmenddate")) then
        rs.Close:set rs=nothing
        conn.Close:set conn=nothing
        response.Write("Error:该帐号已经过期！")
        response.End 
     end if
     end if
     
     endd=rs("drmenddate")
     if isnull(endd) then
        endd=""
     end if 
     
     if fileid<>"" then
        if not isnull(rs("FileIDStr")) and rs("FileIDStr")<>""  and ucase(rs("FileIDStr"))<>"ALL" then
           filearr=split(lcase(rs("FileIDStr")),"|")
           for fid=0 to ubound(filearr)
               if instr("|"&fileid,"|"&filearr(fid))<>0 then
                  pass=true
                  exit for
               end if
           next
           if not pass then
               rs.Close:set rs=nothing
               conn.Close:set conn=nothing
               response.Write("Error:该帐号无权播放此文件！")
               response.End 
           end if
        end if
     end if
     
     '----------------------------------------------------
     danwei=trim(rs("danwei"))
     pcstr=trim(rs("pcstr"))
     pcnum=trim(rs("pcnum"))
     code=request("code")
     
     if isnull(pcstr) then pcstr=""
     
     if int(pcnum)<>0 and instr(pcstr,code)<=0 then
        
        if (ubound(split(pcstr,","))< (int(pcnum)-1)) or pcstr=""  then
        
             if pcstr="" then
               conn.execute("update zp_userinfo set pcstr='"& code &"' where  uid='"& uid &"'")
             else
               conn.execute("update zp_userinfo set pcstr='"& pcstr &","& code &"' where  uid='"& uid &"'")
             end if     
        else
        
               rs.Close:set rs=nothing
               conn.Close:set conn=nothing
               response.Write("Error:已经超过授权的机器数！")
               response.End    
        
        end if
        
     
     end if
     
     '----------------------------------------------------
     
     
    
    
        
 code=request("code") 
 
                                                     
response.write "AAAAAA" & "775BA57F13190638175B156A6619034D774B100F36097618" & "|" & "0" & "|" & "" & "|" & rs("danwei")   & "|" & md5(code)  

 rs.Close:set rs=nothing
 conn.Close : set conn = nothing
%>
 