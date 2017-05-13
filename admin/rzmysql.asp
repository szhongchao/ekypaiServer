<%
'测试读取MySql数据库的内容
strconnection="driver={mysql odbc 5.1 driver};server=localhost;database=kypai;uid=kypai;password=szc743712;"
'无需配置dsn
set adodataconn = server.createobject("adodb.connection")
adodataconn.open strconnection
strquery = "select * from zp_userinfo"
set rs = adodataconn.execute(strquery)
if not rs.bof then
%>
<table>
<tr>
<td<b>QQ号码</b></td>
<td><b>播放密码</b></td>
</tr>
<%
do while not rs.eof
%>
<tr>
<td><%=rs("qqnum")%></td>
<td><%=rs("uid")%></td>
</tr>
<%
rs.movenext
loop
%>
</table>
<%
else
response.write("无数据.")
end if
rs.close
adodataconn.close
set adodataconn = nothing
set rsemaildata = nothing
%> 