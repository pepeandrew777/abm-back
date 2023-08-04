<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AbmAplicacionUsuario extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $table = 'abm_aplicacion_usuario';
    const CREATED_AT = 'd_creacion';
    const UPDATED_AT = 'd_actualizacion';
    protected $dates = ['d_eliminacion'];
    const DELETED_AT = 'd_eliminacion';
    protected $primaryKey = 'n_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'n_id_usuario',
        'n_id_aplicacion'
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
    public function aplicacion(){
        return $this->belongsTo(AbmAplicacion::class,'n_id_aplicacion');
    }
}
