--legat de tabela salvari
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
    
    insert into salvari(id_utilizator,id_animal) values(v_id_utilizator,v_id_animal);
    
    act_statistici_salvari(v_id_utilizator,v_id_animal);
    
    p_raspuns:='OK';
end;

create or replace procedure descarcare_informatii_animal(p_nume_utilizator varchar2,p_nume_animal varchar2,p_raspuns IN OUT varchar2)
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
    
    --operatii necesare pentru a transmte informatiile despre acest animal
    --etc,etc
    
    act_statistici_descarcari(v_id_utilizator,v_id_animal);    
end;

create or replace procedure cautare_dupa_criterii(p_clasa varchar2,p_originne varchar2,p_stare_de_conservare varchar2,p_regim_alimentar varchar2,p_mod_de_inmultire varchar2)
as
begin
    
end;

--test
declare
    v_nume_utilizator varchar2(100):='remus';
    v_nume_animal varchar2(100):='tigru';
    v_raspuns varchar2(100);
begin
    salvare_animal(v_nume_utilizator,v_nume_animal,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

delete from salvari;

declare
    v_nume_utilizator varchar2(100):='remus';
    v_nume_animal varchar2(100):='tigru';
    v_raspuns varchar2(100);
begin
    descarcare_informatii_animal(v_nume_utilizator,v_nume_animal,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

select * from statistici_clase_animale;
delete from statistici_clase_animale;
