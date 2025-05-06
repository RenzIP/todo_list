<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
return new class extends Migration 
{ 
public function up(): void 
{ 
Schema::create('tasks', function (Blueprint $table) { 
$table->id(); 
$table->foreignId('id_user'); 
$table->string('judul'); 
$table->text('deskripsi'); 
$table->date('deadline'); 
$table->enum('status', ['tertunda', 'selesai'])
->default('tertunda'); 
$table->timestamps(); 
}); 
} 
public function down(): void 
{ 
Schema::dropIfExists('tugas'); 
} 
};