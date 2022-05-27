create or replace function salvare_animal(p_nume_utilizator varchar2,p_nume_animal varchar2)
return varchar2
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
    
begin
    
    --selectare id pentru animal,daca exista
    begin
        select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        return 'Nu exista un animal in baza de date cu acest nume';
    end;
    
    --selectarea id pentru utilizator,daca exista
    begin
        select id into v_id_utilizator from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        return 'Nu exista un utilizator in baza de date cu acest nume';
    end;
    
    return 'OK';
end;

create or replace function inregistrare(p_nume_utilizator varchar2,p_parola varchar2,p_email varchar2 default null,p_telefon varchar2 default null)
return varchar2
as
    v_nr integer;
    
    v_id utilizatori.id%type;
begin
    --alternativ,facem insert direct si daca apare exceptia DUP_VAL_ON_INDEX atunci sa spun ca numele e luat
    
    --daca este disponibil
    select count(*) into v_nr from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    
    if(v_nr!=0) then
        return 'Numele de utilizator nu este disponibil';
    end if;
    
    insert into utilizatori(id,nume_utilizator,parola,email,telefon) values(
    (select nvl(max(id),0)+1 from utilizatori),
    p_nume_utilizator,
    p_parola,
    p_email,
    p_telefon);
    
    return 'OK';
end;

create or replace function autentificare(p_nume_utilizator varchar2,p_parola_trimisa varchar2)
return varchar2
as
    v_parola utilizatori.parola%type;
begin
    begin
        select parola into v_parola from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        return 'Nu exista un utilizator cu acest nume';
    end;
    
    if(v_parola!=p_parola_trimisa) then
        return 'Parola incorecta';
    end if;
    
    return 'OK';
end;

create or replace function sterge_utilizator(p_nume_utilizator varchar2) 
return varchar2
as
begin
    delete from utilizatori where trim(nume_utilizator)=trim(p_nume_utilizator);
    
    if(SQL%ROWCOUNT=0) then
        return 'Nu exista un utilizator cu acest nume';
    end if;
    
    return 'OK';    
end;

select * from utilizatori;

declare
    v_ok varchar2(100);
begin
    v_ok := sterge_utilizator('Remus');
    dbms_output.put_line(v_ok);
end;

declare
    v_ok integer :=0;
    v_id_animal animale.id%type;
    p_nume_animal varchar2(20) := 'tigru';
begin
    begin
    select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        v_ok:=1;
    end;
end;