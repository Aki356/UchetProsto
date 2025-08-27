<?php
declare(strict_types=1);

/**
 * Простая библиотека: минимальные классы без пространств имён и автозагрузчика.
 * ООП сохранено, но структура упрощена.
 */

final class Subtopic {
    public function __construct(
        private int $topicId,
        private string $code,
        private string $title
    ) {}
    public function toArray(): array {
        return ['topicId'=>$this->topicId,'code'=>$this->code,'title'=>$this->title];
    }
}

final class Topic {
    /** @var Subtopic[] */
    private array $subs = [];
    public function __construct(private int $id, private string $title) {}
    public function id(): int { return $this->id; }
    public function title(): string { return $this->title; }
    public function add(Subtopic $s): void { $this->subs[] = $s; }
    /** @return Subtopic[] */
    public function subs(): array { return $this->subs; }
    public function toArray(): array {
        return ['id'=>$this->id,'title'=>$this->title,'subtopics'=>array_map(fn($s)=>$s->toArray(),$this->subs)];
    }
}

final class KnowledgeBase {
    /** @var Topic[] */
    private array $topics;
    public function __construct() {
        $t1 = new Topic(1,'Тема 1');
        $t1->add(new Subtopic(1,'1.1','Подтема 1.1'));
        $t1->add(new Subtopic(1,'1.2','Подтема 1.2'));
        $t1->add(new Subtopic(1,'1.3','Подтема 1.3'));
        $t2 = new Topic(2,'Тема 2');
        $t2->add(new Subtopic(2,'2.1','Подтема 2.1'));
        $t2->add(new Subtopic(2,'2.2','Подтема 2.2'));
        $t2->add(new Subtopic(2,'2.3','Подтема 2.3'));
        $this->topics = [$t1,$t2];
    }
    /** @return Topic[] */
    public function topics(): array { return $this->topics; }
    public function defaultTopicId(): int { return 1; }
    public function defaultSubtopicCode(int $topicId): string {
        foreach($this->topics as $t){ if($t->id()===$topicId){ return $t->subs()[0]->toArray()['code']; } }
        return '1.1';
    }
}

final class ContentService {
    public function getBySubtopic(string $code): string {
        if(!preg_match('/^[12]\.[123]$/',$code)) return 'Подтема не найдена.';
        return "Некий текст, привязанный к Подтеме {$code}.";
    }
}
