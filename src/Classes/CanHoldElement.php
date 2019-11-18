<?php

namespace UnserializeFixer;

abstract class CanHoldElement extends LengthElement
{
	public $elements = [];
	
	public function getCorruptedElements(bool $exclude_ignore = false): array
	{
		$corruptedElements = [];
		
		foreach ($this->getElements() as $element) {
			if ($element instanceof CanHoldElement) {
				$corruptedElements[] = $element;
				
				$sub_elements = $element->getCorruptedElements($exclude_ignore);
				foreach ($sub_elements as $sub_element) {
					$corruptedElements[] = $sub_element;
				}
			} elseif ($element instanceof CorruptedElement || $element->getCorrupted() === true) {
				if ($exclude_ignore && $element->getIgnore() === true) {
					continue;
				}
				$corruptedElements[] = $element;
			}
		}
		
		return $corruptedElements;
	}
	
	public function getElements($exclude_ignore = false): ?array
	{
		if ($exclude_ignore === true) {
			$elements = [];
			foreach ($this->elements as $element) {
				if ($element->ignore == null) {
					$elements[] = $element;
				}
			}
			
			return $elements;
		} else {
			return $this->elements;
		}
	}
	
	public function setElements(array $elements): void
	{
		$this->elements = $elements;
	}
	
	public function elementsCheck(): void
	{
		$max_nb_item = $this->getlength() * 2;
		$count_elements = count($this->getElements(true));
		
		if ($count_elements < $max_nb_item) {
			$this->setLength(ceil($count_elements) / 2);
			
			for ($count_elements; $count_elements < $this->getLength(); $count_elements++) {
				$element = new Elements\ElementString();
				$element->setValue('XX_' . rand(0, 9999));
				$element->setLength(strlen($element->getValue()));
				
				$this->addElement($element);
			}
		} elseif ($count_elements > $max_nb_item) {
			$this->setElements(array_slice($this->getElements(true), 0, $max_nb_item));
		}
		
		//check if key is a valid type
		foreach ($this->getElements(true) as $index => $element) {
			if ($index % 2 == 0) {
				if (in_array(get_class($element), ['UnserializeFixer\ElementObject', 'UnserializeFixer\ElementArray', 'UnserializeFixer\ElementNull', 'UnserializeFixer\ElementDecimal', 'UnserializeFixer\ElementBoolean'])) {
					$elements_clean = $this->getElements(true);
					array_splice($elements_clean, $index, 1);
					
					$this->setElements($elements_clean);
				}
			}
		}
		
		
		$indexes = [];
		foreach ($this->getElements(true) as $index => $element) {
			if ($index % 2 == 0) {
				$value = $element->getValue();
				if (in_array($value, $indexes)) {
					throw new Exceptions\DuplicateIndexException($value);
				}
				$indexes[] = $value;
			}
		}
	}
	
	public function addElement(BaseElement $element): void
	{
		$this->elements[] = $element;
	}
	
}
