<?php

namespace UnserializeFixer;

abstract class BaseElement
{
	public $part = null;
	public $corrupted = false;
	public $ignore = false;
	public $cause = null;
	public $fix_try = 0;
	
	public function getCause(): ?string
	{
		return $this->cause;
	}
	
	public function setCause($cause): void
	{
		$this->cause = $cause;
	}
	
	public function getSerialize(): ?string
	{
		if ($this->getIgnore() === false) {
			
			return $this->getSerializeElement();
		} else {
			return null;
		}
	}
	
	public function getIgnore(): bool
	{
		return $this->ignore;
	}
	
	public function setIgnore(bool $ignore): void
	{
		$this->ignore = $ignore;
	}
	
	public function getFixTry(): int
	{
		return $this->fix_try;
	}
	
	function getPart(): string
	{
		return $this->part;
	}
	
	function setPart($part): void
	{
		$this->part = $part;
	}
	
	public function getCorrupted(): bool
	{
		return $this->corrupted;
	}
	
	public function setCorrupted(bool $corrupted): void
	{
		$this->corrupted = $corrupted;
	}
	
	public function repair(): void
	{
		
		$this->incrementFixTry(1);
		
		$this->repairElement();
	}
	
	public function incrementFixTry($increment): void
	{
		$this->fix_try += $increment;
	}
	
}
