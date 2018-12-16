<?php

namespace App\Models;

use App\Favourite;
use App\Helpers\FileValidation;
use App\User;
use function file_get_contents;
use getID3;
use Illuminate\Database\Eloquent\Model;
use function public_path;

class FileUpload extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function size($type = 'mb')
    {
        switch ($type) {
            case "mb":
                return round($this->size_in_bytes / 1024 / 1024, 2);
                break;
            case "kb":
                return round($this->size_in_bytes / 1024, 2);
                break;
        }

        return round($this->size_in_bytes, 2);
    }

    public function previewImage()
    {
        switch ($this->fileType()) {
            case "image":
                return $this->link;
                break;

            case "video":
                return '/images/video-icon.png';
                break;

            case "audio":
                return '/images/audio-icon.png';
                break;

            case "compressed":
                return '/images/compressed-icon.png';
                break;

            case "text":
                return '/images/text-icon.png';
                break;
        }
    }

    public function dimensions()
    {
        if ($this->fileType() == 'image') {
            return cache()->remember('image-dimensions:' . $this->id, 60 * 24, function () {
                $size = getimagesize($this->link);

                return [
                    'width'  => $size[0],
                    'height' => $size[1],
                ];
            });

        }

        /*if ($this->fileType() == 'video') {
            return cache()->remember('video-dimensions:' . $this->id, 60 * 24, function () {
                $id      = new getid3;
                $analyze = $id->analyze(file_get_contents($this->link));

                dd($analyze);

                return [
                    'width'  => $analyze['video']['resolution_x'],
                    'height' => $analyze['video']['resolution_y'],
                    'fps'    => $analyze['video']['frame_rate'],
                    'length' => $analyze['playtime_string'],
                ];
            });
        }*/

        return null;
    }

    public function fileType()
    {
        return app(FileValidation::class)->fileType($this->mime_type);
    }

    public function link()
    {
        return route('file', $this->name);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'favourable_id', 'id')->where('favourable_type', FileUpload::class);
    }

    /**
     * Allow the authed user to "favourite" a file
     *
     * @return bool
     */
    public function favourite()
    {
        $favourite = Favourite::where('user_id', auth()->id())
            ->where('favourable_id', $this->id)
            ->where('favourable_type', FileUpload::class)
            ->first();

        if ($favourite !== null) {
            $favourite->delete();

            return true;
        } else {
            $favourite                  = new Favourite;
            $favourite->user_id         = auth()->id();
            $favourite->favourable_id   = $this->id;
            $favourite->favourable_type = FileUpload::class;
            $favourite->save();

            return true;
        }

    }

    /**
     * All the file views for this file upload
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany(FileView::class);
    }

    /**
     * Save a new view for the current person viewing this file
     */
    public function saveView()
    {
        if ($this->views()->whereIp(request()->ip())->first()) {
            return;
        }

        $view     = new FileView;
        $view->ip = request()->ip();

        $this->views()->save($view);
    }

}