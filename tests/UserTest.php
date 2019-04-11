<?php
class UserTest extends TestCase {
	/**
	 * /users [GET]
	 */
	public function testShouldReturnAllUsers() {
		$this->get("users", []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure([
			'success' => true,
			'users' => ['*' =>
				[
					'id',
					'email',
					'firstname',
					'lastname',
					'title',
					'description',
					'created_at',
					'updated_at',
					'team',
				],
			],
		]);

	}
	/**
	 * /users/id [GET]
	 */
	public function testShouldReturnUser() {
		$this->get("products/1", []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			['user' =>
				[
					'id',
					'email',
					'firstname',
					'lastname',
					'title',
					'description',
					'created_at',
					'updated_at',
					'team',
				],
			]
		);

	}
	/**
	 * /users [POST]
	 */
	public function testShouldCreateUser() {
		$parameters = [
			'firstname' => 'Test',
            'lastname' => 'User'
			'email' => 'testuser@mail.com',
            'title' => 'Test Worker',
            'description'=> 'this is test description',
            'team' => '1'
		];
		$this->post("users", $parameters, []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			[
                'success',
                'error'
			]
		);

	}

	/**
	 * /users/id [PUT]
	 */
	public function testShouldUpdateUser() {
		$parameters = [
			'firstname' => 'Test1',
            'lastname' => 'User1'
            'email' => 'testuser@mail.com',
            'title' => 'Test Worker1',
            'description'=> 'this is test description1',
            'team' => '1'
		];
		$this->put("users/4", $parameters, []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			[
                'success',
                'error'
			]
		);
	}
	/**
	 * /users/id [DELETE]
	 */
	public function testShouldDeleteUser() {

		$this->delete("users/5", [], []);
		$this->seeStatusCode(410);
		$this->seeJsonStructure([
			'success',
			'error',
		]);
	}
}