--pentru statistici

create or replace procedure act_statistici_clase_animale(p_id_animal animale.id%type)
as
    v_nr integer;
    
    v_clasa varchar2(100);
begin
    select clasa into v_clasa from animale where id=p_id_animal;
    
    if(v_clasa is null or length(v_clasa)=0)then
        return;
    end if;
    
    --better with exists,probably
    select count(*) into v_nr from statistici_clase_animale where lower(trim(clasa))=lower(v_clasa);
    
    if(v_nr=0)then
        insert into statistici_clase_animale(clasa,nr_accesari) values(v_clasa,1);
    else    
        update statistici_clase_animale set nr_accesari=nr_accesari+1 where lower(trim(clasa))=lower(v_clasa);
    end if;
end;

create or replace procedure act_statistici_salvari(p_id_utilizator animale.id%type,p_id_animal animale.id%type)
as
begin
    --actualizare numarul de salvari
    update animale set nr_salvari=nr_salvari+1 where id=p_id_animal;
    
    --actualizare numarul de accesari ai clasei din care face parte animalul
    act_statistici_clase_animale(p_id_animal);
end;

create or replace procedure act_statistici_descarcari(p_id_utilizator animale.id%type,p_id_animal animale.id%type)
as
begin
    --actualizare numar de descarcari
    update animale set nr_descarcari=nr_descarcari+1 where id=p_id_animal;
    
    --actualizare numarul de accesari ai clasei din care face parte animalul
    act_statistici_clase_animale(p_id_animal);
end;