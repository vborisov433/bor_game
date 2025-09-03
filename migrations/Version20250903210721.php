<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250903210721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uploaded_file (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', group_type INT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE market_analysis DROP FOREIGN KEY FK_ED82D2F8458B4EB8');
        $this->addSql('ALTER TABLE news_article_info DROP FOREIGN KEY FK_9D81DFE1458B4EB8');
        $this->addSql('DROP TABLE market_summary');
        $this->addSql('DROP TABLE market_analysis');
        $this->addSql('DROP TABLE prompt_template');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE news_item');
        $this->addSql('DROP TABLE news_article_info');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE market_summary (id INT AUTO_INCREMENT NOT NULL, html_result LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', time_loaded INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE market_analysis (id INT AUTO_INCREMENT NOT NULL, news_item_id INT NOT NULL, market VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sentiment VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, magnitude INT NOT NULL, reason LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, keywords JSON NOT NULL COMMENT \'(DC2Type:json)\', categories JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_ED82D2F8458B4EB8 (news_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prompt_template (id INT AUTO_INCREMENT NOT NULL, template LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_seen DATETIME NOT NULL, data LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news_item (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, link VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', gpt_analysis JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', analyzed TINYINT(1) NOT NULL, completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CAC6D39536AC99F1 (link), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE news_article_info (id INT AUTO_INCREMENT NOT NULL, news_item_id INT NOT NULL, has_market_impact TINYINT(1) NOT NULL, title_headline VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, news_surprise_index INT DEFAULT NULL, economy_impact INT DEFAULT NULL, macro_keyword_heatmap JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', summary LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_9D81DFE1458B4EB8 (news_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE market_analysis ADD CONSTRAINT FK_ED82D2F8458B4EB8 FOREIGN KEY (news_item_id) REFERENCES news_item (id)');
        $this->addSql('ALTER TABLE news_article_info ADD CONSTRAINT FK_9D81DFE1458B4EB8 FOREIGN KEY (news_item_id) REFERENCES news_item (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE uploaded_file');
    }
}
