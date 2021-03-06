<?php

namespace App\Model;

use App\Model\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCode extends Model
{
    use SoftDeletes;

    /**
     * Default code_id of qrcode
     */
    const DEFAULT_CODE_ID = 1;

    /**
     * Default QR codes not print yet is 1
     */
    const QR_CODE_NOT_PRINTED = 1;

    /**
     * Default QR codes were printed is 0
     */
    const QR_CODE_PRINTED = 0;

    /**
     * QrCode prefix
     */
    const QRCODE_PREFIX = 'ATB-';

    /**
     * Declare table
     *
     * @var string $tabel table name
     */
    protected $table = 'qrcodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id',
        'prefix',
        'code_id',
        'status',
    ];

    /**
     * Relationship hasOne with Book
     *
     * @return array
    */
    public function book()
    {
        return $this->hasOne(Book::class);
    }

    /**
     * Generate new QrCode for new book
     *
     * @return App\Model\Book
    */
    public static function generateQRCode()
    {
        $lastestQRCode = self::select('code_id')->where('prefix', self::QRCODE_PREFIX)->withTrashed()->orderby('code_id', 'desc')->first();
        $lastestCodeId = $lastestQRCode ? $lastestQRCode->code_id + 1 : QrCode::DEFAULT_CODE_ID;
        return new self([
            'prefix' => QrCode::QRCODE_PREFIX,
            'code_id'=> $lastestCodeId,
        ]);
    }

    /**
     * Save qr for imported list
     *
     * @param array          $qrCode qrcode's attribute
     * @param App\Model\Book $book   book
     *
     * @return void
     */
    public static function saveImportQRCode($qrCode, $book)
    {
        $qrcodeData = [
            'book_id' =>$book->id,
            'prefix' => $qrCode['prefix'],
            'code_id'=> $qrCode['code_id'],
        ];
        self::lockForUpdate()->firstOrCreate($qrcodeData);
    }
    
    /**
     * Filtered QR Codes are not printed
     *
     * @param String $query query
     *
     * @return mixed
    */
    public function scopeQRCodesNotPrinted($query)
    {
        return $query->where('qrcodes.status', QrCode::QR_CODE_NOT_PRINTED);
    }

    /**
     * Merge two property prefix and code id for qrcode
     *
     * @return array
     */
    public function getQrcodeBookAttribute()
    {
        return $this->prefix . $this->code_id;
    }
}
