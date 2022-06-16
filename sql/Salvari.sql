--legat de tabela salvari
--primeste numele unui utilizator si un sir de nume de animale,separate printr-un separator
--returneaza un mesaj de final corespunzator

select * from salvari;

describe utilizatori;


select id from utilizatori where nume_utilizator='remus'


create or replace procedure salvare_animale(p_nume_utilizator varchar2,p_nume_animale varchar2,p_separator varchar2,p_raspuns IN OUT varchar2)
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
    v_nume_animal animale.denumire_populara%type;
    
    v_pozitie integer :=1;
    
    v_nr integer;
begin
    
    --selectarea id pentru utilizator,daca exista
    begin
        select id into v_id_utilizator from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        p_raspuns:='No user exists with this username: '||p_nume_utilizator;
        return;
    end;
    
    --selectare nume a unui animal si salvarea sa,daca exista in baza de date
    loop
        --extrage un nume
        select regexp_substr(p_nume_animale,'[^'||p_separator||']+',1,v_pozitie) into v_nume_animal from dual;
        if(v_nume_animal is null)then
            exit;
        end if;
        
        --selectare id animal
        if(length(v_nume_animal)>0)then
            begin
                select id into v_id_animal from animale where lower(trim(v_nume_animal))=lower(trim(denumire_populara));
            exception
                when no_data_found then
                v_id_animal:=-1;
            end;
            
            --daca exista animal
            if(v_id_animal!=-1)then
                
                --cate aparitii ale acestei salvari se afla in baza de date
                select count(*) into v_nr from salvari where id_utilizator=v_id_utilizator and id_animal=v_id_animal;
                
                --daca nu l-ai mai salvat
                if(v_nr=0)then
                    insert into salvari(id_utilizator,id_animal) values(v_id_utilizator,v_id_animal);
                end if;
            end if;
        end if;
        
        v_pozitie:=v_pozitie+1;
    end loop;
        
    p_raspuns:='OK';
end;
set serveroutput on;


declare
    v_nume_utilizator varchar2(100):='sumer';
    v_nume_animale varchar2(100):='tigrud,,,,lesu';
    v_separator varchar2(1):=',';
    v_raspuns varchar2(100);
begin
    salvare_animale(v_nume_utilizator,v_nume_animale,v_separator,v_raspuns);
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

describe animale;

describe user_tables;
select * from user_column;
