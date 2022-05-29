create or replace procedure salvare_animal(p_nume_utilizator varchar2,p_nume_animal varchar2,p_raspuns IN OUT varchar2)
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
    
begin
    
    --selectare id pentru animal,daca exista
    begin
        select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        p_raspuns:='Nu exista un animal in baza de date cu acest nume';
        return;
    end;
    
    --selectarea id pentru utilizator,daca exista
    begin
        select id into v_id_utilizator from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        p_raspuns:='Nu exista un utilizator in baza de date cu acest nume';
        return;
    end;
    
    p_raspuns:='OK';
end;

--------------------legate de utilizatori

create or replace procedure inregistrare(p_nume_utilizator varchar2,p_parola varchar2,p_email varchar2 default null,p_telefon varchar2 default null,p_raspuns OUT varchar2)
as
    v_nr integer;
    
    v_id utilizatori.id%type;
begin
    --alternativ,facem insert direct si daca apare exceptia DUP_VAL_ON_INDEX atunci sa spun ca numele e luat
    
    --daca este disponibil
    select count(*) into v_nr from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    
    if(v_nr!=0) then
        p_raspuns:='Numele de utilizator nu este disponibil';
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
        p_raspuns:='Nu exista un utilizator cu acest nume';
        return;
    end;
    
    if(v_parola!=p_parola_trimisa) then
        p_raspuns:='Parola incorecta';
    end if;
    
    return 'OK';
end;

create or replace procedure sterge_utilizator(p_nume_utilizator varchar2,p_raspuns OUT varchar2)
as
begin
    delete from utilizatori where trim(nume_utilizator)=trim(p_nume_utilizator);
    
    if(SQL%ROWCOUNT=0) then
        p_raspuns:='Nu exista un utilizator cu acest nume';
        return;
    end if;
    
    p_raspuns:='OK';    
end;
-------

create or replace procedure schimbare_nume_utilizator(p_nume_curent varchar2,p_nume_nou varchar2,p_raspuns IN OUT varchar2)
as
    v_nr integer;
    v_id_utilizator utilizatori.id%type;
begin
    --mai este disponibil numele cerut?
    select count(*) into v_nr from utilizatori where trim(p_nume_nou)=trim(nume_utilizator);
    
    if(v_nr!=0)then
        p_raspuns:='Numele de utilizator introdus nu mai este disponibil';
        return;
    end if;
    
    --update la nume in functie de cel vechi
    
    update utilizatori set nume_utilizator=p_nume_nou where trim(nume_utilizator)=trim(p_nume_curent);
    
    if(sql%rowcount=0) then
        p_raspuns:='Nu exista un utilizator cu acest nume in baza de date';
    else
        p_raspuns:='OK';
    end if;
end;

--p_nume_camp=nume_utilizator,schimba numele,daca e disponibil numele nou
--p_nume_camp=parola,schimba parola
--p_nume_camp=email,schimba email
--p_nume_camp=telefon,schimba nr telefon
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
        p_raspuns:='Nu exista un utilizator cu acest nume';
        return;
    end if;

    if(p_nume_camp is null)then
        p_raspuns:='Numele campului modificat este null';
        return;
    end if;
    
    v_nume_camp:=lower(trim(p_nume_camp));
    
    if(v_nume_camp!='nume_utilizator' and v_nume_camp!='parola' and v_nume_camp!='email' and v_nume_camp!='telefon') then
        p_raspuns:='Numele campului nu este recunoscut';
        return;
    end if;
    
    if(p_valoare_camp is null)then
        p_raspuns:='Valoarea campului este nula';
        return;
    end if;
    
    if(length(p_valoare_camp)=0)then
        p_raspuns:='Valoarea campului este goala';
        return;
    end if;
    
    if(v_nume_camp='nume_utilizator')then
        --mai este disponibil numele cerut?
        select count(*) into v_nr from utilizatori where trim(p_valoare_camp)=trim(nume_utilizator);
    
        if(v_nr!=0)then
            p_raspuns:='Numele de utilizator introdus nu mai este disponibil';
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


--------------------------for testing

select name,line,text from user_source where lower(name)='schimbare_camp_utilizator' order by line asc;

declare
    v_nume_utilizator varchar2(100):='remus';
    v_nume_camp varchar2(100):='telefon';
    v_valoare_camp varchar2(100):='123';
    v_raspuns varchar2(100);
begin
    schimbare_camp_utilizator(v_nume_utilizator,v_nume_camp,v_valoare_camp,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

select * from utilizatori where id<5;

set serveroutput on;
declare
    v_nume_vechi varchar2(100):='daniel';
    v_nume_nou varchar2(100):='remus';
    v_raspuns varchar2(100);
begin
    schimbare_nume_utilizator(v_nume_vechi,v_nume_nou,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

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