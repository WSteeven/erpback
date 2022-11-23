-- vista para mostrar los items del inventario con sus respectivas perchas
create or replace view view_inventario_percha as
select i.id, i.detalle_id ,i.sucursal_id ,i.cliente_id ,i.por_recibir ,i.por_entregar ,i.condicion_id , i.estado, 
	pep.stock as cantidad, 
	u2.codigo 
	from inventarios i , productos_en_perchas pep , ubicaciones u2 
where pep.inventario_id =i.id and 
pep.ubicacion_id = u2.id 

-- consultar la vista
-- select * from view_inventario_percha 