

drop function testing;

create or replace procedure testing(sir1 varchar2,sir2 varchar2,rezultat IN OUT varchar2) 
as
begin
    rezultat:=sir1||' '||sir2;
end;

set serveroutput on;
declare
    sir1 varchar2(100) := 'Hello';
    sir2 varchar2(100) := 'World';
    rezultat varchar2(100);
begin
    testing(sir1,sir2,rezultat);
    dbms_output.put_line(rezultat);
end;

create or replace function testing2(sir1 varchar2,sir2 varchar2,sir3 varchar2 default 'ok',sir4 varchar2 default 'ok')
return varchar2
as
begin
    return sir1||' '||sir2;
end;

select testing2('Hello','World') from dual;