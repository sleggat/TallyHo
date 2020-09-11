<?php

class Task {
	private $client;
	private $project;
	private $date;
	private $dateadded;
	private $dateupdated;
	private $duration;
	private $description;
	private $path;

	public function __construct($array)
	{
		$this->client = $array['Client'];
		$this->project = $array['Project'];
		$this->date = $array['Date'];
		$this->duration = $array['Duration'];
		$this->description = $array['Description'];
		$this->path = $array['Path'];
	}

	public function getClient()
	{
		return $this->client;
	}

	public function getProject()
	{
		return $this->project;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getPath()
	{
		return $this->path;
	}
	
}