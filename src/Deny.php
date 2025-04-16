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
        if (!file_exists($this->getDenyPath())) {
            $this->clearDenyList();
        }

        $deny_list = file($this->getDenyPath());

        return is_array($deny_list) ? $deny_list : throw new \Exception("Could not open deny list at path: {$this->deny_list_path}");
    }
    
    /**
     * addDeny
     *
     * @param  string $ip
     * @return bool
     */
    public function addDeny(string $ip): bool
    {
        if(!$this->isValidCidr($ip)) {
            throw new \Exception("Invalid IP or CIDR notation: {$ip}");
        }

        file_put_contents($this->getDenyPath(), "deny {$ip};" . PHP_EOL, FILE_APPEND) ? true : throw new \Exception("Could write deny list at path: {$this->getDenyPath()}");

        return true;
    }
    
    /**
     * removeDeny
     *
     * @param  string $ip
     * @return bool
     */
    public function removeDeny(string $ip): bool
    {
        $deny_list = $this->getDenyList();
        $deny_list = array_filter($deny_list, function ($line) use ($ip) {
            return trim($line) !== "deny {$ip};";
        });

        file_put_contents($this->getDenyPath(), implode(PHP_EOL, $deny_list)) ? true : throw new \Exception("Could write updated deny list at path: {$this->getDenyPath()}");

        return true;
    }
    
    /**
     * clearDenyList
     *
     * @return bool
     */
    public function clearDenyList(): bool
    {
        file_put_contents($this->getDenyPath(), '') ? true : throw new \Exception("Could clear deny list entry at path: {$this->getDenyPath()}");

        return true;
    }

    public function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public function isValidCidr(string $cidr): bool
    {
        // Split IP and subnet mask
        $parts = explode('/', $cidr);
        
        // Check if we have exactly 2 parts
        if (count($parts) !== 2) {
            return false;
        }
        
        [$ip, $mask] = $parts;
        
        // Validate IP portion
        if (!$this->isValidIp($ip)) {
            return false;
        }
        
        // Validate subnet mask
        if (!is_numeric($mask)) {
            return false;
        }
        
        $mask = (int) $mask;
        
        // IPv4
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $mask >= 0 && $mask <= 32;
        }
        
        // IPv6
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $mask >= 0 && $mask <= 128;
        }
        
        return false;
    }
        
}
