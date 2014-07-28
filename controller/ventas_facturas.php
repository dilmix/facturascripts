<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2014  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_model('factura_cliente.php');

class ventas_facturas extends fs_controller
{
   public $factura;
   public $offset;
   public $resultados;
   
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Facturas de cliente', 'ventas', FALSE, TRUE);
   }
   
   protected function process()
   {
      $this->custom_search = TRUE;
      $this->factura = new factura_cliente();
      
      if( isset($_GET['delete']) )
      {
         $fact = $this->factura->get($_GET['delete']);
         if($fact)
         {
            if( $fact->delete() )
               $this->new_message("Factura eliminada correctamente.");
            else
               $this->new_error_msg("¡Imposible eliminar la factura!");
         }
         else
            $this->new_error_msg("¡Factura no encontrada!");
      }
      
      $this->buttons[] = new fs_button('b_nueva', 'Nueva', 'index.php?page=nueva_venta');
      $this->buttons[] = new fs_button('b_huecos', 'Huecos');
      
      $this->offset = 0;
      if( isset($_GET['offset']) )
         $this->offset = intval($_GET['offset']);
      
      if($this->query != '')
         $this->resultados = $this->factura->search($this->query, $this->offset);
      else
         $this->resultados = $this->factura->all($this->offset);
   }
   
   public function anterior_url()
   {
      $url = '';
      if($this->query!='' AND $this->offset>'0')
         $url = $this->url()."&query=".$this->query."&offset=".($this->offset-FS_ITEM_LIMIT);
      else if($this->query=='' AND $this->offset>'0')
         $url = $this->url()."&offset=".($this->offset-FS_ITEM_LIMIT);
      return $url;
   }
   
   public function siguiente_url()
   {
      $url = '';
      if($this->query!='' AND count($this->resultados)==FS_ITEM_LIMIT)
         $url = $this->url()."&query=".$this->query."&offset=".($this->offset+FS_ITEM_LIMIT);
      else if($this->query=='' AND count($this->resultados)==FS_ITEM_LIMIT)
         $url = $this->url()."&offset=".($this->offset+FS_ITEM_LIMIT);
      return $url;
   }
}