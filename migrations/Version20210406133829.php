<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406133829 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hashtags (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_21E12BEF389B783 (tag), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT NOT NULL, publicat_date DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_hashtags (news_id INT NOT NULL, hashtags_id INT NOT NULL, INDEX IDX_68DF88BEB5A459A0 (news_id), INDEX IDX_68DF88BE65827D0B (hashtags_id), PRIMARY KEY(news_id, hashtags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_hashtags ADD CONSTRAINT FK_68DF88BEB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news_hashtags ADD CONSTRAINT FK_68DF88BE65827D0B FOREIGN KEY (hashtags_id) REFERENCES hashtags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news_hashtags DROP FOREIGN KEY FK_68DF88BE65827D0B');
        $this->addSql('ALTER TABLE news_hashtags DROP FOREIGN KEY FK_68DF88BEB5A459A0');
        $this->addSql('DROP TABLE hashtags');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_hashtags');
    }
}
