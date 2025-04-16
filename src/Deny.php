<?php

namespace Turbo124\Waffy;

class Deny
{

    private string $deny_list_path = '/etc/nginx/blacklist/denylist.conf';

    public function __construct()
    {
    }
    
    /**
     * getDenyPath
     *
     * @return string
     */
    public function getDenyPath(): string
    {
        return $this->deny_list_path;
    }
    
    /**
     * setDenyPath
     *
     * @param  string $path
     * @return self
     */
    public function setDenyPath(string $path): self
    {
        $this->deny_list_path = $path;

        return $this;
    }
    
    /**
     * getDenyList
     *
     * @return array
     */
    public function getDenyList(): array
    {
        if (!file_exists($this->deny_list_path)) {
            $this->clearDenyList();
        }

        return file($this->deny_list_path) ?? throw new \Exception("Could not open deny list at path: {$this->deny_list_path}");
    }
    
    /**
     * addDeny
     *
     * @param  string $ip
     * @return self
     */
    public function addDeny(string $ip): self
    {
        file_put_contents($this->getDenyPath(), "deny {$ip};" . PHP_EOL, FILE_APPEND) ?? throw new \Exception("Could write deny list at path: {$this->getDenyPath()}");

        return $this;
    }
    
    /**
     * removeDeny
     *
     * @param  string $ip
     * @return self
     */
    public function removeDeny(string $ip): self
    {
        $deny_list = $this->getDenyList();
        $deny_list = array_filter($deny_list, function ($line) use ($ip) {
            return trim($line) !== $ip;
        });

        file_put_contents($this->deny_list_path, implode(PHP_EOL, $deny_list)) ?? throw new \Exception("Could write updated deny list at path: {$this->getDenyPath()}");

        return $this;
    }
    
    /**
     * clearDenyList
     *
     * @return self
     */
    public function clearDenyList(): self
    {
        file_put_contents($this->deny_list_path, '') ?? throw new \Exception("Could clear deny list entry at path: {$this->getDenyPath()}");

        return $this;
    }
        
}
