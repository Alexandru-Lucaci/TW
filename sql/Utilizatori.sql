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


--pentru inregistare
declare
    v_nume_utilizator varchar2(100):='paul';
    v_parola varchar2(100):='paul';
    v_email varchar2(100):=null;
    v_telefon varchar2(100):=null; 
    v_raspuns varchar2(100);
begin
    inregistrare(v_nume_utilizator,v_parola,v_email,v_telefon,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

--pentru stergere
declare
    v_nume_utilizator varchar2(100):='paul';
    v_raspuns varchar2(100);
begin
    sterge_utilizator(v_nume_utilizator,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;


--pentru schimbare valoare camp
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