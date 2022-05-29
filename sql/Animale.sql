--legat de animale
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

--testing