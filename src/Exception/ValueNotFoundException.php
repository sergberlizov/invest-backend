<?php


namespace App\Exception;


class ValueNotFoundException extends \Exception
{
    private $date;

    private $assetName;

    private $debugInfo;

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        $this->updateMessage();

        return $this;
    }

    public function setAssetName(string $assetName): self
    {
        $this->assetName = $assetName;
        $this->updateMessage();

        return $this;
    }

    public function setDebugInfo(string $debugInfo): self
    {
        $this->debugInfo = $debugInfo;
        $this->updateMessage();

        return $this;
    }

    private function updateMessage(): void
    {
        $this->message = sprintf(
            'Value not found %s %s %s' . PHP_EOL,
            $this->getDate()->format('Y-m-d'),
            $this->assetName,
            $this->debugInfo
        );;
    }

}