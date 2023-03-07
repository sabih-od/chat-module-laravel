<?php

namespace ChatModule\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class isValidMedia implements Rule
{
    protected $message = 'Invalid media file!';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) return false;
        $is_image = Validator::make(
            ['upload' => $value],
            ['upload' => 'image']
        )->passes();

        $is_video = Validator::make(
            ['upload' => $value],
            ['upload' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4']
        )->passes();

        if (!$is_video && !$is_image) {
            $this->message = ':attribute must be image or video.';
            return false;
        }

        if ($is_video) {
            $validator = Validator::make(
                ['video' => $value],
                ['video' => "max:102400"]
            );
            if ($validator->fails()) {
                $this->message = ':attribute must be 10 megabytes or less.';
                return false;
            }
        }

        if ($is_image) {
            $validator = Validator::make(
                ['image' => $value],
                ['image' => "max:102400"]
            );
            if ($validator->fails()) {
                $this->message = ':attribute must be 10 megabytes or less.';
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
