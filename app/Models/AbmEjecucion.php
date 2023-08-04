<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbmEjecucion extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $table = 'abm_ejecucion';
    const CREATED_AT = 'd_creacion';
    const UPDATED_AT = 'd_actualizacion';
    protected $dates = ['d_eliminacion'];
    const DELETED_AT = 'd_eliminacion';

    protected $primaryKey = 'n_id';
    protected $appends = ['fecha_ejecucion'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'd_fecha_ejecucion',
        'n_id_usuario',
        'v_nombre',
        'n_nro_asignacion'        
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'd_creacion',
        'd_actualizacion',
        'd_eliminacion',
    ];
    public function usuario(){
        return $this->belongsTo(AbmUsuario::class,'n_id_usuario');
    }
    public function getFechaEjecucionAttribute(){
        return date('d/m/Y', strtotime($this->d_fecha_ejecucion));
    }

}
