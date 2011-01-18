<?php

class Bench_EntityHydration extends Testes_Benchmark_Test
{
    /**
     * The hydration data that is set up during setUp().
     * 
     * @var array
     */
	protected $data = array(
		'simple'     => array(),
		'complex'    => array(),
		'simpleset'  => array(),
		'complexset' => array()
	);
    
    /**
     * Sets up hydration data for different benchmarks.
     * 
     * @return void
     */
	public function setUp()
	{
		$this->data['simple']     = $this->simpledata();
		$this->data['complex']    = $this->complexdata();
		for ($i = 0; $i < 1000; $i++) {
			$this->data['simpleset'][]  = $this->data['simple'];
			$this->data['complexset'][] = $this->data['complex'];
		}
	}
    
	public function tearDown()
	{
		
	}

	/**
	 * Benchmarks a simple user getting hydrated with basic information.
	 * 
	 * No custom properties are assigned to this user and no behaviors
	 * were invoked.
	 * 
	 * @return void
	 */
	public function simpleUser1000Times()
	{
		for ($i = 0; $i < 1000; $i++) {
			$entity = new Provider_UserBasic;
			$entity->import($this->data['simple']);
		}
	}

	/**
	 * Benchmarks a simple user getting hydrated with basic information.
	 * 
	 * No custom properties were assigned to this user and no behaviors
	 * were invoked. This is the same user as "benchmarkSimpleUser1000Times"
	 * but using the same data that was used for the complex user with
	 * complex data.
	 * 
	 * @return void
	 */
	public function simpleUser1000TimesWithComplexData()
	{
		for ($i = 0; $i < 1000; $i++) {
			$entity = new Provider_UserBasic;
			$entity->import($this->data['complex']);
		}
	}

	/**
	 * Benchmarks a simple user getting hydrated with basic information.
	 * 
	 * Custom properties and behaviors are being used, but the same data
	 * that was used for the simple user and simple data was used.
	 * 
	 * @return void
	 */
	public function complexUser1000Times()
	{
		for ($i = 0; $i < 1000; $i++) {
			$entity = new Provider_User;
			$entity->import($this->data['simple']);
		}
	}

	/**
	 * Benchmarks a simple user getting hydrated with basic information.
	 * 
	 * Custom properties and behaviors are being used. The same data used
	 * for the simple user, complex data benchmark was used making this
	 * the most complicated benchmark.
	 * 
	 * @return void
	 */
	public function complexUser1000TimesWithComplexData()
	{
		for ($i = 0; $i < 1000; $i++) {
			$entity = new Provider_User;
			$entity->import($this->data['complex']);
		}
	}

	public function simpleUserSetSimpleData()
	{
		return new Model_EntitySet('Provider_UserBasic', $this->data['simpleset']);
	}

	public function simpleUserSetComplexData()
	{
		return new Model_EntitySet('Provider_UserBasic', $this->data['complexset']);
	}

	public function complexUserSetSimpleData()
	{
		return new Model_EntitySet('Provider_User', $this->data['simpleset']);
	}

	public function complexUserSetComplexData()
	{
		return new Model_EntitySet('Provider_User', $this->data['complexset']);
	}

	public function simpleUserSetSimpleDataExport()
	{
		return $this->simpleUserSetSimpleData()->export();
	}

	public function simpleUserSetComplexDataExport()
	{
		return $this->simpleUserSetComplexData()->export();
	}

	public function complexUserSetSimpleDataExport()
	{
		return $this->complexUserSetSimpleData()->export();
	}

	public function complexUserSetComplexDataExport()
	{
		return $this->complexUserSetComplexData()->export();
	}

	/**
	 * Returns a simple data array for hydration.
	 * 
	 * This array doesn't contain any relationships or complex data 
	 * structures.
	 * 
	 * @return array
	 */
	protected function simpledata()
	{
		return array(
			'id'       => md5(microtime()),
			'name'     => 'Tres Hugart',
			'dob'      => '1983-01-02 11:00:00',
			'email'    => 'test@test.com'
		);
	}

	/**
	 * Returns a simple data array for hydration.
	 * 
	 * There are multiple levels of relationships, so there are multiple
	 * instantiations and hydrations happening for a single object that
	 * this is passed to, given the relationships are set up.
	 * 
	 * @return array
	 */
	protected function complexdata()
	{
		return array_merge(
			$this->simpledata(),
			array(
				'homepage' => array(
					'user'    => md5(microtime()),
					'title'   => 'My Personal Homepage!',
					'content' => 'My content! Yay!'
				),
				'friends'  => array(
					array(
						'user'   => md5(microtime() + 1),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 2),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 3),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 4),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 5),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 6),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 7),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 8),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 9),
						'status' => 1
					),
					array(
						'user'   => md5(microtime() + 10),
						'status' => 1
					)
				)
			)
		);
	}
}