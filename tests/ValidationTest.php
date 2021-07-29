<?php
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{

    public function testRuleRequired()
    {
        $data = [
            "campo1" => "Lorem",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required",
            "campo2" => "required",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(false,$validate->fails());

        $data = [
            "campo1" => "Lorem",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required",
            "campo2" => "",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(false,$validate->fails());
    }

    public function testRuleRequiredFields()
    {
        $data = [
            "campo1" => "Lorem",
            "campo2" => "",
        ];
        $rules = [
            "campo1" => "required",
            "campo2" => "required",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleMin()
    {
        $data = [
            "campo1" => "5",
            "campo2" => "6",
        ];
        $rules = [
            "campo1" => "required|min:2",
            "campo2" => "required|min:10",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleMax()
    {
        $data = [
            "campo1" => "5",
            "campo2" => "6",
        ];
        $rules = [
            "campo1" => "required|max:10",
            "campo2" => "required|max:2",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleMinlenght()
    {
        $data = [
            "campo1" => "Lorem",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|minlenght:2",
            "campo2" => "required|minlenght:10",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleMaxlenght()
    {
        $data = [
            "campo1" => "Lorem",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|maxlenght:10",
            "campo2" => "required|maxlenght:2",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleRegex()
    {
        $data = [
            "campo1" => "Lorem Ipson",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|regex:/^[a-zA-Z\s]{2,12}$/",
            "campo2" => "required|regex:/^[0-9]$/",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleDateformat()
    {
        $data = [
            "campo1" => "2021-07-01",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|date_format:Y-m-d",
            "campo2" => "required|date_format:Y-m-d",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleTimeformat()
    {
        $data = [
            "campo1" => "05:30",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|time_format:Hi",
            "campo2" => "required|time_format:Hi",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testRuleEmail()
    {
        $data = [
            "campo1" => "lorem@gmail.com",
            "campo2" => "Lorem",
        ];
        $rules = [
            "campo1" => "required|email",
            "campo2" => "required|email",
        ];
        $validate = Validation::make($data,$rules);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertArrayHasKey('campo2', $errors);
    }
    public function testFields()
    {
        $data = [
            "campo1" => "Lorem",
            "campo2" => "",
        ];
        $rules = [
            "campo1" => "required",
            "campo2" => "required",
        ];
        $fields = [
            "campo1" => "Campo 1",
            "campo2" => "Campo 2",
        ];
        $validate = Validation::make($data,$rules,$fields);
        $this->assertEquals(true,$validate->fails());
        $errors = $validate->errors;
        $this->assertEquals('Campo 2 es obligatorio.', $errors['campo2']);
    }
}

