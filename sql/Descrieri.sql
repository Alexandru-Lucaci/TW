--legate de tabela descrieri

create or replace procedure adaugare_descriere(p_descriere varchar2,p_limba varchar2,p_raspuns IN OUT varchar2)
as
    v_id descrieri.id%type;
begin
    
    select nvl(max(id),0)+1 into v_id from descrieri;

    if(p_descriere is null) then
        p_raspuns:='Descriere data este vida';
        return;
    end if;
   
    if(length(p_descriere)=0) then
        p_raspuns:='Descrierea data este goala';
        return;
    end if;
   
    if(p_limba is null) then
        p_raspuns:='Valoarea pentru limba este vida';
        return;
    end if;
    
    if(length(p_limba)=0) then
        p_raspuns:='Valoarea pentru limba este goala';
        return;
    end if;
    
    insert into descrieri(id,descriere,limba) values(v_id,p_descriere,p_limba);
    
    p_raspuns:='OK';
end;

--test

declare 
    v_descriere varchar2(4000):='test';
    v_limba varchar2(20):='test';
    v_raspuns varchar2(100);
begin
    adaugare_descriere(v_descriere,v_limba,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

delete from descrieri where limba='test';