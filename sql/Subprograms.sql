create or replace function salvare_animal(p_nume_utilizator varchar2,p_nume_animal varchar2)
return varchar2
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
    
    v_valid integer := 1;
begin
    
    --selectare id pentru animal,daca exista
    select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    if(SQL%NOTFOUND) then
        return 'Nu exista un animal cu acest nume in baza de date';
    end if;
    
    --selectare id pentru utilizator,daca exista
    select id into v_id_utilizator from utilizatori where lower(trim(p_nume_utilizator))=lower(trim(nume_utilizator));
    if(SQL%NOTFOUND) then
        return 'Nu exista un utilizator cu acest nume in baza de date';
    end if;
    
    --salvare
    
    return 'OK';
end;

set serveroutput on;

declare
    v_ok varchar2(30);
begin
    v_ok := salvare_animal('remus','tigru');
    dbms_output.put_line(v_ok);
end;
