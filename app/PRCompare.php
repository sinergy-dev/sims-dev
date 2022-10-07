<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PrProduct;
use App\PRProductCompare;
use App\PrDokumen;
use App\PRDocumentCompare;

class PRCompare extends Model
{
    protected $table = 'tb_pr_compare';
	
	protected $primaryKey = 'id';
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'to',
		'email',
		'phone',
		'fax',
		'attention',
		'title',
		'address',
		'term_payment',
		'nominal',
		'note_pembanding'
	];

	public function product(){
        return $this->hasMany('App\PRProductCompare','id_compare_pr','id')->orderBy('id');
    }

    public function getProductDetailAttribute()
    {
    	$data = PRCompare::join('tb_pr_product_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
        		->join('tb_pr_product', 'tb_pr_product.id', '=', 'tb_pr_product_compare.id_product')
        		->select('name_product', 'qty', 'unit', 'tb_pr_product.description', 'serial_number', 'part_number', 'nominal_product', 'grand_total', 'tb_pr_compare.id')
        		->where('tb_pr_compare.id', $this->id)
        		->get();

        return $data;
    }

    public function document()
    {
    	return $this->hasMany('App\PRDocumentCompare','id_compare_pr','id')->orderBy('id');
    }

    public function getDocumentDetailAttribute()
    {
    	$data = PRCompare::join('tb_pr_document_compare', 'tb_pr_document_compare.id_compare_pr', '=', 'tb_pr_compare.id')
    			->join('tb_pr_document', 'tb_pr_document.id', '=', 'tb_pr_document_compare.id_document')
    			->select('dokumen_name', 'dokumen_location', 'tb_pr_compare.id', 'link_drive')
    			->where('tb_pr_compare.id', $this->id)
    			->get();

    	return $data;
    }
}
