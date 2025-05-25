<?php
namespace App\Enums;

enum ModelProvider: string
{
    case OPENAI = 'openai';
    case GEMINI = 'gemini';
    case ANTHROPIC = 'anthropic';
}
