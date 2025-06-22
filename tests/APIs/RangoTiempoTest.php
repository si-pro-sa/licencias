<?php

namespace Tests\APIs;

use Tests\TestCase;
use App\Models\RangoTiempo;

class RangoTiempoTest extends TestCase
{

    /** @test */
    public function no_tiene_interposicion_horaria_7_13_y_20_8()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('20:00', '08:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_7_13_y_7_13()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('07:00', '13:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_6_15_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '15:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_7_13_y_8_14()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_9_13_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '13:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_9_15_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '15:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_23_11_y_8_14()
    {
        $rango1 = new RangoTiempo('23:00', '11:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_20_2_y_19_7()
    {
        $rango1 = new RangoTiempo('20:00', '02:00');
        $rango2 = new RangoTiempo('19:00', '07:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function puesto_tiene_interposicion_horaria_lv_08_17_con_guardia_16_04()
    {
        $rango1 = new RangoTiempo('08:00', '17:00');
        $rango2 = new RangoTiempo('16:00', '04:00');
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    //FALSOS
    /** @test */
    public function no_tiene_interposicion_horaria_6_8_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_14_18_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '18:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_6_7_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '07:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_17_18_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '18:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_14_8_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_17_8_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_7_13_y_7_13()
    {
        $rango1 = new RangoTiempo('07:00', '13:00', true);
        $rango2 = new RangoTiempo('07:00', '13:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_6_15_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '15:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_7_13_y_8_14()
    {
        $rango1 = new RangoTiempo('07:00', '13:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_9_13_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '13:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_9_15_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '15:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function tiene_interposicion_horaria_con_dia_siguiente_no_laborable_20_2_y_19_7()
    {
        $rango1 = new RangoTiempo('20:00', '02:00', true);
        $rango2 = new RangoTiempo('19:00', '07:00', true);
        $this->assertTrue($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_23_11_y_8_14()
    {
        //Falsos
        $rango1 = new RangoTiempo('23:00', '11:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_7_13_y_20_8()
    {
        $rango1 = new RangoTiempo('07:00', '13:00', true);
        $rango2 = new RangoTiempo('20:00', '08:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_6_8_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '08:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_14_18_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '18:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_6_7_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '07:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_17_18_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '18:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_14_8_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '08:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_17_8_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '08:00', true);
        $rango2 = new RangoTiempo('08:00', '14:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function no_tiene_interposicion_horaria_con_dia_siguiente_no_laborable_20_8_y_7_13()
    {
        $rango1 = new RangoTiempo('20:00', '08:00', true);
        $rango2 = new RangoTiempo('07:00', '13:00', true);
        $this->assertFalse($rango1->tieneInterposicionConRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_7_13_y_7_13()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('07:00', '13:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_9_13_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '13:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_7_13_y_7_14()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('07:00', '14:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_9_14_y_7_14()
    {
        $rango1 = new RangoTiempo('09:00', '14:00');
        $rango2 = new RangoTiempo('07:00', '14:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_20_2_y_19_7()
    {
        $rango1 = new RangoTiempo('20:00', '02:00');
        $rango2 = new RangoTiempo('19:00', '07:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_1_7_y_19_7()
    {
        $rango1 = new RangoTiempo('01:00', '07:00');
        $rango2 = new RangoTiempo('19:00', '07:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_1_6_y_19_7()
    {
        $rango1 = new RangoTiempo('01:00', '06:00');
        $rango2 = new RangoTiempo('19:00', '07:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_7_13_30_y_7_19()
    {
        $rango1 = new RangoTiempo('07:00', '13:30');
        $rango2 = new RangoTiempo('07:00', '19:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_7_13_30_y_0_23_59()
    {
        $rango1 = new RangoTiempo('07:00', '13:30');
        $rango2 = new RangoTiempo('00:00', '23:59:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function esta_dentro_de_rango_7_13_30_y_6_6()
    {
        $rango1 = new RangoTiempo('07:00', '13:30');
        $rango2 = new RangoTiempo('06:00', '06:00');
        $this->assertTrue($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_9_15_y_8_14()
    {
        $rango1 = new RangoTiempo('09:00', '15:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_6_15_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '15:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_7_13_y_8_14()
    {
        $rango1 = new RangoTiempo('07:00', '13:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_6_8_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_14_18_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '18:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_6_7_y_8_14()
    {
        $rango1 = new RangoTiempo('06:00', '07:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_17_18_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '18:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_14_8_y_8_14()
    {
        $rango1 = new RangoTiempo('14:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }

    /** @test */
    public function no_esta_dentro_de_rango_17_8_y_8_14()
    {
        $rango1 = new RangoTiempo('17:00', '08:00');
        $rango2 = new RangoTiempo('08:00', '14:00');
        $this->assertFalse($rango1->estaDentroDeRango($rango2));
    }
}
