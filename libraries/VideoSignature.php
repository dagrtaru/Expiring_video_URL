<?php
    /*
        This class allows us to:
            -Encrypt filepaths and expiration dates into signature
            -Decrypt signatures into filepaths and expiration dates
    */
    class VideoSignature
    {
        //Time before a video expires in seconds
        private $expires = 10;
        //Keys used to sign URLs
        private $encryption_key = 'soMeStr0ngP455W0rD!!';
        //Public URL used to serve the content
        private $proxy_url = '/getVideo.php';

        //Following function encrypts a string
        private function encryptStr($str)
        {
            $iv = mcrypt_create_iv(
                mcrypt_create_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
                MCRYPT_DEV_URANDOM
            );

            $encrypted = base64_encode(
                $iv.mcrypt_encrypt(MCRYPT_RIJNADAEL_128, 
                hash('sha256', $this->encryption_key, true),
                $str,
                MCRYPT_MODE_CBC,
                $iv)
            );
            return $encrypted;
        }

        //Following function decrypts a string
        private function decryptStr($str)
        {
            $data = base64_decode($str);
            $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

            $decrypted = rtrim(
                mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128,
                    hash('sha256', $this->encryption_key, true),
                    substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
                    MCRYPT_MODE_CBC,
                    $iv
                ), "\0"
            );
            return $decrypted;
        }

        //Following function returns a temporary URL
        public function getSignedURL($filepath)
        {
            $data = json_encode(
                array(
                    "filepath" => $filepath,
                    "expires" => time() + $this->expires
                )
            );
            $signature = $this->encryptStr($data);
            return $this->proxy_url . "?s=" . urlencode($signature);
        } 

        //Returns a filepath from a signature if it did not expire
        public function getFilePath($signature)
        {
            $data = json_decode($this->decryptStr($signature), true);

            if($data !== null && $data['expires'] > time() && file_exists($data['filepath'])){
                return $data['filepath'];
            }
            return false;
        }
    }
?>