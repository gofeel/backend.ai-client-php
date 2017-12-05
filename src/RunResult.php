<?php 
namespace BackendAI;

class RunResult
{
    private $stdout = "";
    private $stderr = "";
    private $media = "";
    private $waitingInput = false;
    private $continuation = false;
    private $dataStr = "";
    private $data = [];

    public function __construct($data)
    {
        $this->dataStr = $data;
    }

    public function asArray()
    {
        $this->parseData();
        return $this->data;
    }

    public function asJsonString()
    {
        return $this->dataStr;
    }

    private function parseData()
    {
        if (count($this->data) != 0) {
            return;
        }
        $this->data = json_decode($this->dataStr, true);
        if (isset($this->data['result']['status'])) {
            $this->status = $this->data['result']['status'];

            if ($this->status == "continued") {
                $this->continuation = true;
                $this->waitingInput = false;
            } elseif ($this->status == "waiting-input") {
                $this->continuation = true;
                $this->waitingInput = true;
            } else {
                $this->continuation = false;
                $this->waitingInput = false;
            }

            if (isset($this->data['result']['console'])) {
                foreach ($this->data['result']['console'] as $k=>$v) {
                    if ($v[0] == 'stdout') {
                        $this->stdout = $v[1];
                    } elseif ($v[0] == 'stderr') {
                        $this->stderr = $v[1];
                    } elseif ($v[0] == 'media') {
                        if ($v[1][0] === "image/svg+xml") {
                            $this->media = $v[1][1];
                        }
                    }
                }
            }
        }
    }

    public function isFinished()
    {
        $this->parseData();
        return (!$this->continuation);
    }

    public function getStatus()
    {
        $this->parseData();
        return $this->status;
    }

    public function getStdout()
    {
        $this->parseData();
        return $this->stdout;
    }

    public function getStderr()
    {
        $this->parseData();
        return $this->stderr;
    }
}
