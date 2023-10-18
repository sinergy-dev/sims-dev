<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PrProduct;
use App\PRProductCompare;
use App\PrDokumen;
use App\PRDocumentCompare;
use DB;

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

	// public function product(){
 //        return $this->hasMany('App\PRProductCompare','id_compare_pr','id')->orderBy('id');
 //    }

    public function getProductDetailAttribute()
    {
    	$data = PRCompare::join('tb_pr_product_compare', 'tb_pr_compare.id', '=', 'tb_pr_product_compare.id_compare_pr')
        		->join('tb_pr_product', 'tb_pr_product.id', '=', 'tb_pr_product_compare.id_product')
        		->select('name_product', 'qty', 'unit', 'tb_pr_product.description', DB::raw("(CASE WHEN (serial_number is null) THEN '-' ELSE serial_number END) as serial_number"), DB::raw("(CASE WHEN (part_number is null) THEN '-' ELSE part_number END) as part_number"), 'nominal_product', 'grand_total', 'tb_pr_compare.id')
        		->where('tb_pr_compare.id', $this->id)
        		->get();

        return $data;
    }

    // public function document()
    // {
    // 	return $this->hasMany('App\PRDocumentCompare','id_compare_pr','id')->orderBy('id');
    // }

    public function getDocumentDetailAttribute()
    {
    	$getIdDraft = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_compare.id_draft_pr')->select('id_draft_pr', 'type_of_letter')->where('tb_pr_compare.id', $this->id)->first();

    	$data = PRCompare::join('tb_pr_document_compare', 'tb_pr_document_compare.id_compare_pr', '=', 'tb_pr_compare.id')
    			->join('tb_pr_document', 'tb_pr_document.id', '=', 'tb_pr_document_compare.id_document')
    			->select('dokumen_name', 'dokumen_location',  'tb_pr_compare.id', 'link_drive')
    			->where('tb_pr_compare.id', $this->id)
    			->get();

        $dokumen = PRCompare::join('tb_pr_document_compare', 'tb_pr_document_compare.id_compare_pr', '=', 'tb_pr_compare.id')
                ->join('tb_pr_document', 'tb_pr_document.id', '=', 'tb_pr_document_compare.id_document')
                ->select('dokumen_name', 'dokumen_location', 'tb_pr_document.id as id_dokumen', 'tb_pr_compare.id', 'link_drive')->where('tb_pr_compare.id', $this->id);

        if ($getIdDraft->type_of_letter == 'IPR') {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();

            // $getDokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
            //     ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
            //     ->select('dokumen_name', 'dokumen_location', 'link_drive')
            //     ->where('tb_pr_document_draft.id_draft_pr', $getIdDraft->id_draft_pr)
            //     ->where(function($query){
            //         $query->where('dokumen_name', '!=','Penawaran Harga');
            //     })
            //     ->orderBy('tb_pr_document.created_at','asc')
            //     ->get();

            $getDokumen = PrDokumen::join('tb_pr_document_compare', 'tb_pr_document_compare.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_compare', 'tb_pr_compare.id', 'tb_pr_document_compare.id_compare_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive', 'tb_pr_document.id as id_dokumen')
                ->where('tb_pr_compare.id_draft_pr', $getIdDraft->id_draft_pr)
                ->where(function($query){
                    $query->where('dokumen_name', '!=', 'Penawaran Harga');
                })
                ->get();

            return $getAll;
        } else {
            $get_id_max = DB::table($dokumen, 'temp')->groupBy('dokumen_name')->selectRaw('MAX(`temp`.`id_dokumen`) as `id_dokumen`');
            $getAll = DB::table($get_id_max, 'temp2')->join('tb_pr_document', 'tb_pr_document.id', '=', 'temp2.id_dokumen')->select('dokumen_name', 'dokumen_location', 'temp2.id_dokumen', 'link_drive')->orderBy('created_at','asc')->get();

            $getDokumen = PrDokumen::join('tb_pr_document_draft', 'tb_pr_document_draft.id_document', '=', 'tb_pr_document.id')
                ->join('tb_pr_draft', 'tb_pr_draft.id', 'tb_pr_document_draft.id_draft_pr')
                ->select('dokumen_name', 'dokumen_location', 'link_drive')
                ->where('tb_pr_document_draft.id_draft_pr', $getIdDraft->id_draft_pr)
                ->where(function($query){
                    $query->where('dokumen_name', '!=','Quote Supplier');
                })
                ->orderBy('tb_pr_document.created_at','asc')
                ->get();

            return array_merge($getAll->toArray(),$getDokumen->toArray());
        }

		
    }
}
