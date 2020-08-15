<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Validation\Rule;

class RuanganSidang extends BaseModel
{
    protected $table = 'ruangan_sidang';

    protected $fillable = [
        'gedung', 'ruangan', 'created_by',
    ];

    public $timestamps = false;

    public function messages(string $key, string $keyTwo = null) {
        switch($key) {
            case 'validation':
                return [
                    'gedung.required' => 'Harap pilih gedung yang akan dibuat',
                    'ruangan.required' => 'Harap isi ruangan',
                    'ruangan.unique' => 'Harap isi ruangan lain atau pilih gedung lain karena sudah terdapat pada sistem',
                    'ruangan.max' => 'Ruangan tidak boleh melebihi 255 karakter!',
                ];
            case 'success':
                switch($keyTwo) {
                    case 'create':
                        return 'Telah melakukan penambahan ruangan sidang dengan sukses!';
                        break;
                    case 'update':
                        return 'Telah melakukan perubahan ruangan sidang dengan sukses!';
                        break;
                    case 'delete':
                        return 'Telah melakukan penghapusan ruangan sidang dengan sukses!';
                        break;
                    default:
                        break;
                }
            break;
            case 'failDelete':
                return 'ruangan ini telah digunakan!';
            default: 
                break;
        }
    }
    
    /**
     * rules
     * when update if same id can update
     * when insert will check unique base column and gedung already exist or not
     */
    public function rules(RuanganSidang $rs) {
        if(isset($rs->id)) {
            return [
                'gedung' => 'required',
                'ruangan' => 'required|max:255|unique:ruangan_sidang,ruangan,'.$rs->id.',id,gedung,'.$rs->gedung,
            ];
        } else {
            return [
                'gedung' => 'required',
                'ruangan' => ['required', 'max:255', 
                                Rule::unique('ruangan_sidang')->where(function($query) use($rs) {
                                    return $query->where('gedung', $rs->gedung)->where('ruangan', $rs->ruangan);
                                }),],
            ];       
        }
    }
}
