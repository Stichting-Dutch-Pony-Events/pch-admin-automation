<?php

namespace App\DataAccessLayer\Pretix\Views;

class OrderPositionAnswer
{
    private int $questionId;
    private string $answer;
    private string $questionIdentifier;
    private array $options = [];

    public function __construct(object $item)
    {
        $this->questionId = $item->question;
        $this->answer = $item->answer;
        $this->questionIdentifier = $item->question_identifier;

        if(property_exists($item, 'options') && is_array($item->options)) {
            for ($i = 0, $iMax = count($item->options); $i < $iMax; $i++) {
                $this->options[$item->options[$i]] = $item->option_identifiers[$i] ?? null;
            }
        }
    }

    /**
     * @return int
     */
    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @return string
     */
    public function getQuestionIdentifier(): string
    {
        return $this->questionIdentifier;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


}
