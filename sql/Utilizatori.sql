create or replace procedure inregistrare(p_nume_utilizator varchar2,p_parola varchar2,p_email varchar2 default null,p_telefon varchar2 default null,p_raspuns OUT varchar2)
as
    v_nr integer;
    
    v_id utilizatori.id%type;
begin
    --alternativ,facem insert direct si daca apare exceptia DUP_VAL_ON_INDEX atunci sa spun ca numele e luat
    
    --daca este disponibil
    select count(*) into v_nr from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    
    if(v_nr!=0) then
        p_raspuns:='Numele de utilizator nu este disponibil(luat)';
        return ;
    end if;
    
    insert into utilizatori(id,nume_utilizator,parola,email,telefon) values(
    (select nvl(max(id),0)+1 from utilizatori),
    p_nume_utilizator,
    p_parola,
    p_email,
    p_telefon);
    
    p_raspuns:='OK';
end;

-------
create or replace procedure autentificare(p_nume_utilizator varchar2,p_parola_trimisa varchar2,p_raspuns OUT varchar2)
as
    v_parola utilizatori.parola%type;
begin
    begin
        select parola into v_parola from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        p_raspuns:='Numele de utilizator '||chr(39)||p_nume_utilizator||chr(39)||' nu exista';
        return;
    end;
    
    if(v_parola!=p_parola_trimisa) then
        p_raspuns:='Parola gresita';
        return;
    end if;
    
    p_raspuns:='OK';
end;

create or replace procedure sterge_utilizator(p_nume_utilizator varchar2,p_raspuns OUT varchar2)
as
begin
    delete from utilizatori where trim(nume_utilizator)=trim(p_nume_utilizator);
    
    if(SQL%ROWCOUNT=0) then
        p_raspuns:='Utilizatorul cu numele '||chr(39)||p_nume_utilizator||chr(39)||' nu exista';
        return;
    end if;
    
    p_raspuns:='OK';    
end;

--p_nume_camp=nume_utilizator,schimba numele,daca e disponibil numele nou
--p_nume_camp=parola,schimba parola
--p_nume_camp=email,schimba email
--p_nume_camp=telefon,schimba nr telefon
--nu merge cu triggere deoarece folosim pachetul dbms_sql ,care pare sa nu fie prins de ele
create or replace procedure schimbare_camp_utilizator(p_nume_utilizator varchar2,p_nume_camp varchar2,p_valoare_camp varchar2,p_raspuns IN OUT varchar2)
as
    v_nr integer;
    
    v_nume_camp varchar2(100);
    
    v_id_cursor integer;
    v_ok integer;
begin

    --mai este disponibil numele cerut?
    select count(*) into v_nr from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    
    if(v_nr=0)then
        p_raspuns:='Numele de utilizator '||chr(39)||p_nume_utilizator||chr(39)||' nu exista';
        return;
    end if;

    if(p_nume_camp is null)then
        p_raspuns:='Numele campului care va fi modificat este null';
        return;
    end if;
    
    v_nume_camp:=lower(trim(p_nume_camp));
    
    if(v_nume_camp!='nume_utilizator' and v_nume_camp!='parola' and v_nume_camp!='email' and v_nume_camp!='telefon') then
        p_raspuns:='Numele campului nu esta valid.Considera alegand dintre:nume_utilizator,parola,email,telefon';
        return;
    end if;
    
    if(p_valoare_camp is null)then
        p_raspuns:='Valoarea noua este nula';
        return;
    end if;
    
    if(length(p_valoare_camp)=0)then
        p_raspuns:='Valoarea noua este goala';
        return;
    end if;
    
    if(v_nume_camp='nume_utilizator')then
        --mai este disponibil numele cerut?
        select count(*) into v_nr from utilizatori where trim(p_valoare_camp)=trim(nume_utilizator);
    
        if(v_nr!=0)then
            p_raspuns:='Numele de utilizator '||chr(39)||p_nume_utilizator||chr(39)||'nu este disponibil';
            return;
        end if;
    end if;
    
    --update la camp in folosind DBMS_SQL
    v_id_cursor:=dbms_sql.open_cursor;
    dbms_sql.parse(v_id_cursor,'update utilizatori set '||v_nume_camp||'='||chr(39)||p_valoare_camp||chr(39)||' where nume_utilizator='||chr(39)||p_nume_utilizator||chr(39),dbms_sql.native);
    v_ok:=dbms_sql.execute(v_id_cursor);
    dbms_sql.close_cursor(v_id_cursor);

    --assume everything works :( ... for now
    p_raspuns:='OK';    
end;

describe utilizatori;

create or replace function obtine_id_utilizator(p_nume_utilizator varchar2)
return utilizatori.id%type
as
    v_id utilizatori.id%type;
begin
    select id into v_id from utilizatori where trim(nume_utilizator)=trim(p_nume_utilizator);
    return v_id;
exception
    when no_data_found then
    return -1;
    when too_many_rows then
    return -1;
end;

select * from salvari;
--------------------------for testing

describe animale;
describe salvari;
describe utilizatori;
--pentru inregistare

select * from animale;

declare
    v_nume_utilizator varchar2(100):='sumer';
    v_denumire_populara varchar2(100):='sumer';
    v_denumire_stintifica varchar2(100):='sumer';
    v_mini_descriere varchar2(100):='sumer';
begin
    select denumire_populara,denumire_stintifica,mini_descriere into v_denumire_populara,v_denumire_stintifica,v_mini_descriere
    from animale a
    join salvari s on id_utilizator=obtine_id_utilizator(v_nume_utilizator) and id=id_animal;
    
    dbms_output.put_line(v_denumire_populara||v_denumire_stintifica||v_mini_descriere);
end;

set serveroutput on;

--pentru stergere
declare
    v_nume_utilizator varchar2(100):='paul';
    v_raspuns varchar2(100);
begin
    sterge_utilizator(v_nume_utilizator,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;


--pentru schimbare valoare camp

delete from utilizatori;
select * from utilizatori;

select * from animale;

select * from utilizatori where nume_utilizator='tre';

select name,line,text from user_source where lower(name)='schimbare_camp_utilizator' order by line asc;
declare
    v_nume_utilizator varchar2(100):='paul';
    v_nume_camp varchar2(100):='email';
    v_valoare_camp varchar2(100):='paul.paul';
    v_raspuns varchar2(100);
begin
    schimbare_camp_utilizator(v_nume_utilizator,v_nume_camp,v_valoare_camp,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

select * from utilizatori where id<5;