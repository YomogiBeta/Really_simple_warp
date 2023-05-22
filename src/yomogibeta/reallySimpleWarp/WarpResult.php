<?php

namespace yomogibeta\reallySimpleWarp;

class WarpResult{

    private bool $succsess;
    private	string $placeName;

    public function __construct(bool $succsess, string $placeName){
        $this->succsess = $succsess;
        $this->placeName = $placeName;
    }

    public function getSuccsess(): bool{
        return $this->succsess;
    }

    public function getPlaceName(): string{
        return $this->placeName;
    }
}

?>
